<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreChirpstackUplinkRequest;
use App\Http\Resources\MeterReadingResource;
use App\Models\Meter;
use App\Models\MeterReading;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class ChirpstackUplinkController extends Controller
{
    /**
     * Store the reading carried by a ChirpStack uplink.
     */
    public function __invoke(StoreChirpstackUplinkRequest $request): JsonResponse|Response
    {
        if (! $request->isUplink()) {
            return response()->noContent();
        }

        $payload = $request->validated();
        $devEui = strtolower($payload['deviceInfo']['devEui']);

        $meter = Meter::where('dev_eui', $devEui)->first();

        if ($meter === null) {
            // The meter inventory is the authority: an uplink from a device we
            // never registered is a provisioning problem, so make it visible
            // instead of swallowing the reading.
            Log::warning('Uplink received from an unknown device.', ['dev_eui' => $devEui]);

            return response()->json([
                'message' => "No meter is registered with devEui {$devEui}.",
            ], Response::HTTP_NOT_FOUND);
        }

        $object = $payload['object'];
        $gateway = $payload['rxInfo'][0] ?? [];

        // The same uplink can be delivered more than once, always under the same
        // deduplicationId, so keying on it keeps ingestion idempotent.
        $reading = MeterReading::firstOrCreate(
            ['deduplication_id' => $payload['deduplicationId']],
            [
                'meter_id' => $meter->id,
                'volume_net_m3' => $object['volumeNet_m3'] ?? $object['volumeForward_m3'],
                'volume_forward_m3' => $object['volumeForward_m3'] ?? null,
                'volume_reverse_m3' => $object['volumeReverse_m3'] ?? null,
                'flow_rate_lh' => $object['flowRate_Lh'] ?? null,
                'water_temperature_c' => $object['waterTemperature_C'] ?? null,
                'battery_pct' => $object['battery_pct'] ?? null,
                'message_type' => $object['messageType'] ?? null,
                'alarms_raw' => $object['alarmsRaw'] ?? null,
                'alarms' => $object['alarms'] ?? null,
                'active_alarms' => $object['activeAlarms'] ?? null,
                'f_cnt' => $payload['fCnt'] ?? null,
                'rssi' => $gateway['rssi'] ?? null,
                'snr' => $gateway['snr'] ?? null,
                'gateway_id' => $gateway['gatewayId'] ?? null,
                'recorded_at' => $payload['time'],
            ],
        );

        return (new MeterReadingResource($reading))
            ->response()
            ->setStatusCode($reading->wasRecentlyCreated
                ? Response::HTTP_CREATED
                : Response::HTTP_OK);
    }
}

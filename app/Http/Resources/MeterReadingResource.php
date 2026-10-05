<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\MeterReading */
class MeterReadingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'meter_id' => $this->meter_id,
            'deduplication_id' => $this->deduplication_id,
            'volume_net_m3' => $this->volume_net_m3,
            'volume_forward_m3' => $this->volume_forward_m3,
            'volume_reverse_m3' => $this->volume_reverse_m3,
            'flow_rate_lh' => $this->flow_rate_lh,
            'water_temperature_c' => $this->water_temperature_c,
            'battery_pct' => $this->battery_pct,
            'message_type' => $this->message_type,
            'alarms_raw' => $this->alarms_raw,
            'alarms' => $this->alarms,
            'active_alarms' => $this->active_alarms,
            'signal' => [
                'f_cnt' => $this->f_cnt,
                'rssi' => $this->rssi,
                'snr' => $this->snr,
                'gateway_id' => $this->gateway_id,
            ],
            'recorded_at' => $this->recorded_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),

            // Who the reading belongs to, so a listing of arriving readings is
            // readable without a second round of lookups.
            'meter' => $this->whenLoaded('meter', fn () => [
                'id' => $this->meter->id,
                'serial_number' => $this->meter->serial_number,
                'dev_eui' => $this->meter->dev_eui,
                'status' => $this->meter->status->value,
                'service' => $this->meter->service === null ? null : [
                    'id' => $this->meter->service->id,
                    'service_number' => $this->meter->service->service_number,
                    'address' => $this->meter->service->address,
                    'subscriber' => [
                        'id' => $this->meter->service->subscriber->id,
                        'full_name' => $this->meter->service->subscriber->full_name,
                        'identification' => $this->meter->service->subscriber->identification,
                    ],
                ],
            ]),
        ];
    }
}

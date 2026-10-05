<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MeterReadingResource;
use App\Models\Meter;
use App\Models\MeterReading;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MeterReadingController extends Controller
{
    /**
     * List the readings, newest first.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $filters = $request->validate([
            'meter_id' => ['sometimes', 'uuid'],
            'dev_eui' => ['sometimes', 'string', 'max:16'],
            'from' => ['sometimes', 'date'],
            'to' => ['sometimes', 'date'],
        ]);

        $readings = MeterReading::query()
            ->with('meter.service.subscriber')
            ->when(
                isset($filters['meter_id']),
                fn (Builder $query) => $query->where('meter_id', $filters['meter_id']),
            )
            ->when(
                isset($filters['dev_eui']),
                fn (Builder $query) => $query->whereHas(
                    'meter',
                    fn (Builder $meter) => $meter->where('dev_eui', strtolower($filters['dev_eui'])),
                ),
            )
            ->when(
                isset($filters['from']),
                fn (Builder $query) => $query->where('recorded_at', '>=', $filters['from']),
            )
            ->when(
                isset($filters['to']),
                fn (Builder $query) => $query->where('recorded_at', '<=', $filters['to']),
            )
            ->orderByDesc('recorded_at')
            ->paginate(50);

        return MeterReadingResource::collection($readings);
    }

    /**
     * Show the given reading, with the meter it came from.
     */
    public function show(MeterReading $meterReading): MeterReadingResource
    {
        return new MeterReadingResource($meterReading->load('meter.service.subscriber'));
    }

    /**
     * List the readings of a single meter, newest first.
     */
    public function forMeter(Meter $meter): AnonymousResourceCollection
    {
        $readings = $meter->readings()
            ->with('meter.service.subscriber')
            ->orderByDesc('recorded_at')
            ->paginate(50);

        return MeterReadingResource::collection($readings);
    }
}

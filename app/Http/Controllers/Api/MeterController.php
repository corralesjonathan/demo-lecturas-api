<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMeterRequest;
use App\Http\Requests\UpdateMeterRequest;
use App\Http\Resources\MeterResource;
use App\Models\Meter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class MeterController extends Controller
{
    /**
     * List the meters, optionally filtered by organization or status.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $meters = Meter::query()
            ->when(
                $request->filled('organization_id'),
                fn ($query) => $query->where('organization_id', $request->input('organization_id')),
            )
            ->when(
                $request->filled('status'),
                fn ($query) => $query->where('status', $request->input('status')),
            )
            ->orderBy('serial_number')
            ->paginate(15);

        return MeterResource::collection($meters);
    }

    /**
     * Store a newly created meter.
     */
    public function store(StoreMeterRequest $request): JsonResponse
    {
        $meter = Meter::create($request->validated());

        // status and chirpstack_registered fall back to the database defaults.
        $meter->refresh();

        return (new MeterResource($meter))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Show the given meter.
     */
    public function show(Meter $meter): MeterResource
    {
        return new MeterResource($meter);
    }

    /**
     * Update the given meter.
     */
    public function update(UpdateMeterRequest $request, Meter $meter): MeterResource
    {
        $meter->update($request->validated());

        return new MeterResource($meter);
    }
}

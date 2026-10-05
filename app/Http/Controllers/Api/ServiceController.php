<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ServiceController extends Controller
{
    /**
     * List the services, optionally filtered by subscriber or status.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $services = Service::query()
            ->when(
                $request->filled('subscriber_id'),
                fn ($query) => $query->where('subscriber_id', $request->input('subscriber_id')),
            )
            ->when(
                $request->filled('status'),
                fn ($query) => $query->where('status', $request->input('status')),
            )
            ->orderBy('service_number')
            ->paginate(15);

        return ServiceResource::collection($services);
    }

    /**
     * Store a newly created service.
     */
    public function store(StoreServiceRequest $request): JsonResponse
    {
        $service = Service::create($request->validated());

        // status falls back to the database default when not supplied.
        $service->refresh();

        return (new ServiceResource($service))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Show the given service.
     */
    public function show(Service $service): ServiceResource
    {
        return new ServiceResource($service);
    }

    /**
     * Update the given service.
     */
    public function update(UpdateServiceRequest $request, Service $service): ServiceResource
    {
        $service->update($request->validated());

        return new ServiceResource($service);
    }
}

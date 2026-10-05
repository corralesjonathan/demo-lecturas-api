<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubscriberRequest;
use App\Http\Requests\UpdateSubscriberRequest;
use App\Http\Resources\SubscriberResource;
use App\Models\Subscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class SubscriberController extends Controller
{
    /**
     * List the subscribers, optionally filtered by organization.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $subscribers = Subscriber::query()
            ->when(
                $request->filled('organization_id'),
                fn ($query) => $query->where('organization_id', $request->input('organization_id')),
            )
            ->orderBy('full_name')
            ->paginate(15);

        return SubscriberResource::collection($subscribers);
    }

    /**
     * Store a newly created subscriber.
     */
    public function store(StoreSubscriberRequest $request): JsonResponse
    {
        $subscriber = Subscriber::create($request->validated());

        return (new SubscriberResource($subscriber))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Show the given subscriber.
     */
    public function show(Subscriber $subscriber): SubscriberResource
    {
        return new SubscriberResource($subscriber);
    }

    /**
     * Update the given subscriber.
     */
    public function update(UpdateSubscriberRequest $request, Subscriber $subscriber): SubscriberResource
    {
        $subscriber->update($request->validated());

        return new SubscriberResource($subscriber);
    }
}

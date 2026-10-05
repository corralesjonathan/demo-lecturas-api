<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrganizationRequest;
use App\Http\Requests\UpdateOrganizationRequest;
use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class OrganizationController extends Controller
{
    /**
     * List the organizations.
     */
    public function index(): AnonymousResourceCollection
    {
        $organizations = Organization::orderBy('display_name')->paginate(15);

        return OrganizationResource::collection($organizations);
    }

    /**
     * Store a newly created organization.
     */
    public function store(StoreOrganizationRequest $request): JsonResponse
    {
        $organization = Organization::create($request->validated());

        return (new OrganizationResource($organization))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Show the given organization.
     */
    public function show(Organization $organization): OrganizationResource
    {
        return new OrganizationResource($organization);
    }

    /**
     * Update the given organization.
     */
    public function update(UpdateOrganizationRequest $request, Organization $organization): OrganizationResource
    {
        $organization->update($request->validated());

        return new OrganizationResource($organization);
    }
}

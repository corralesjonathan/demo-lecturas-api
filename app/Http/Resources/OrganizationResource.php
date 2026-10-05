<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Organization */
class OrganizationResource extends JsonResource
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
            'chirpstack_tenant_id' => $this->chirpstack_tenant_id,
            'chirpstack_application_id' => $this->chirpstack_application_id,
            'legal_id' => $this->legal_id,
            'legal_name' => $this->legal_name,
            'display_name' => $this->display_name,
            'contact_phone' => $this->contact_phone,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}

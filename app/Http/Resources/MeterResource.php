<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Meter */
class MeterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * The app_key is deliberately left out: it is a LoRaWAN secret and the API
     * never needs to hand it back to a client.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'organization_id' => $this->organization_id,
            'service_id' => $this->service_id,
            'serial_number' => $this->serial_number,
            'dev_eui' => $this->dev_eui,
            'chirpstack_device_profile_id' => $this->chirpstack_device_profile_id,
            'chirpstack_device_name' => $this->chirpstack_device_name,
            'device_serial_number' => $this->device_serial_number,
            'model' => $this->model,
            'status' => $this->status->value,
            'chirpstack_registered' => $this->chirpstack_registered,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrganizationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'chirpstack_tenant_id' => ['required', 'uuid', 'unique:organizations,chirpstack_tenant_id'],
            'chirpstack_application_id' => ['required', 'uuid'],
            'legal_id' => ['required', 'string', 'max:10', 'unique:organizations,legal_id'],
            'legal_name' => ['required', 'string', 'max:200'],
            'display_name' => ['required', 'string', 'max:100'],
            'contact_phone' => ['nullable', 'string', 'max:20'],
        ];
    }
}

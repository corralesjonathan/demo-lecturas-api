<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrganizationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $organization = $this->route('organization');

        return [
            'chirpstack_tenant_id' => [
                'sometimes',
                'uuid',
                Rule::unique('organizations', 'chirpstack_tenant_id')->ignore($organization),
            ],
            'chirpstack_application_id' => ['sometimes', 'uuid'],
            'legal_id' => [
                'sometimes',
                'string',
                'max:10',
                Rule::unique('organizations', 'legal_id')->ignore($organization),
            ],
            'legal_name' => ['sometimes', 'string', 'max:200'],
            'display_name' => ['sometimes', 'string', 'max:100'],
            'contact_phone' => ['nullable', 'string', 'max:20'],
        ];
    }
}

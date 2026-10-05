<?php

namespace App\Http\Requests;

use App\Enums\MeterStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMeterRequest extends FormRequest
{
    /**
     * ChirpStack reports the DevEUI as lowercase hex, so store it that way.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('dev_eui')) {
            $this->merge(['dev_eui' => strtolower(trim((string) $this->input('dev_eui')))]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $meter = $this->route('meter');

        return [
            'organization_id' => ['nullable', 'uuid', 'exists:organizations,id'],
            'service_id' => [
                'nullable',
                'uuid',
                'exists:services,id',
                Rule::unique('meters', 'service_id')->ignore($meter),
            ],
            'serial_number' => [
                'sometimes',
                'string',
                'max:50',
                Rule::unique('meters', 'serial_number')->ignore($meter),
            ],
            'dev_eui' => [
                'sometimes',
                'string',
                'regex:/^[0-9a-f]{16}$/',
                Rule::unique('meters', 'dev_eui')->ignore($meter),
            ],
            'app_key' => ['sometimes', 'string', 'regex:/^[0-9a-fA-F]{32}$/'],
            'chirpstack_device_profile_id' => ['nullable', 'uuid'],
            'chirpstack_device_name' => ['nullable', 'string', 'max:100'],
            'device_serial_number' => ['nullable', 'integer', 'min:0'],
            'model' => ['nullable', 'string', 'max:100'],
            'status' => ['sometimes', Rule::enum(MeterStatus::class)],
            'chirpstack_registered' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * Get the custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'dev_eui.regex' => 'The dev eui must be exactly 16 hexadecimal characters.',
            'app_key.regex' => 'The app key must be exactly 32 hexadecimal characters.',
            'service_id.unique' => 'That service already has a meter assigned.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateServiceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $service = $this->route('service');

        return [
            'subscriber_id' => ['sometimes', 'uuid', 'exists:subscribers,id'],
            'service_number' => [
                'sometimes',
                'string',
                'max:30',
                Rule::unique('services', 'service_number')->ignore($service),
            ],
            'address' => ['sometimes', 'string', 'max:250'],
            'status' => ['sometimes', 'string', 'max:20'],
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'subscriber_id' => ['required', 'uuid', 'exists:subscribers,id'],
            'service_number' => ['required', 'string', 'max:30', 'unique:services,service_number'],
            'address' => ['required', 'string', 'max:250'],
            'status' => ['sometimes', 'string', 'max:20'],
        ];
    }
}

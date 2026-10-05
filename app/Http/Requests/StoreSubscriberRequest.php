<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSubscriberRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'organization_id' => ['required', 'uuid', 'exists:organizations,id'],
            'identification' => [
                'required',
                'string',
                'max:20',
                Rule::unique('subscribers', 'identification')
                    ->where('organization_id', $this->input('organization_id')),
            ],
            'full_name' => ['required', 'string', 'max:150'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:150'],
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
            'identification.unique' => 'The identification is already registered for this organization.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSubscriberRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $subscriber = $this->route('subscriber');

        return [
            'organization_id' => ['sometimes', 'uuid', 'exists:organizations,id'],
            'identification' => [
                'sometimes',
                'string',
                'max:20',
                Rule::unique('subscribers', 'identification')
                    ->where('organization_id', $this->input('organization_id', $subscriber->organization_id))
                    ->ignore($subscriber),
            ],
            'full_name' => ['sometimes', 'string', 'max:150'],
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

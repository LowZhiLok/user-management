<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;

        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => [
                'sometimes',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'phone_number' => [
                'sometimes',
                'string',
                'min:7',
                'max:15',
                'regex:/^[0-9]+$/',
                Rule::unique('users', 'phone_number')->ignore($userId),
            ],
            'password' => ['sometimes', 'nullable', 'string', 'min:8'],
            'status' => ['sometimes', 'in:active,inactive'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone_number.regex' => 'The phone number may only contain numbers.',
            'phone_number.min' => 'The phone number must be at least 7 digits.',
            'phone_number.max' => 'The phone number may not be greater than 15 digits.',
        ];
    }
}

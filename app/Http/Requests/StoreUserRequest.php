<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone_number' => ['required', 'string', 'min:7', 'max:15', 'regex:/^[0-9]+$/', 'unique:users,phone_number'],
            'password' => ['required', 'string', 'min:8'],
            'status' => ['required', 'in:active,inactive'],
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

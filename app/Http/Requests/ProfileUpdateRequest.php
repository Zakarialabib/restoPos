<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->user()->id),
            ],
            'phone' => [
                'required',
                'string',
                'regex:/^[0-9]{10,15}$/',
                Rule::unique('users')->ignore($this->user()->id),
            ],
            'password' => [
                'nullable',
                'string',
                Password::defaults(),
                'confirmed',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'This email is already taken.',
            'phone.unique' => 'This phone number is already registered.',
            'phone.regex' => 'Please enter a valid phone number.',
            'password.confirmed' => 'The password confirmation does not match.',
        ];
    }
}

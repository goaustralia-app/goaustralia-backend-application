<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
                Rule::exists('email_verifications', 'email')
                    ->where(fn ($query) => $query->where('is_verified', 1)),
            ],
            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->letters()
                    ->numbers()
                    ->symbols(),
            ],

            'country_id' => ['required', 'integer', 'exists:countries,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.exists' => 'This email address has not been verified.',
            'email.unique' => 'This email address is already registered.',
        ];
    }
}

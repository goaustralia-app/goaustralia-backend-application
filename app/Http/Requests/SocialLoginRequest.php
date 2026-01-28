<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SocialLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'provider' => ['required', 'string', 'in:google,facebook,apple'],
            'access_token' => ['required', 'string'],
            'country_id' => ['nullable', 'integer', 'exists:countries,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'provider.required' => 'Social login provider is required.',
            'provider.in' => 'The selected provider is invalid. Supported providers are: google, facebook, apple.',
            'access_token.required' => 'Access token from social provider is required.',
            'country_id.exists' => 'The selected country is invalid.',
        ];
    }
}

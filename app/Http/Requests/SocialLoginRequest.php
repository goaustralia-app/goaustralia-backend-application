<?php

namespace App\Http\Requests;

use App\Enums\ProviderNameEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SocialLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'provider_name' => ['required', 'string', Rule::in(ProviderNameEnum::getAllValues())],
            'provider_id' => ['required', 'string', 'max:2048'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'country_id' => ['nullable', 'integer', 'exists:countries,id', 'min:1'],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'provider_name' => strtolower(trim($this->provider_name ?? '')),
            'provider_id' => trim($this->provider_id ?? ''),
            'name' => trim($this->name ?? ''),
            'email' => strtolower(trim($this->email ?? '')),
        ]);
    }

    public function messages(): array
    {
        return [
            'provider_name.required' => 'Social login provider is required.',
            'provider_name.in' => 'The selected provider is invalid. Supported providers are: google, facebook, apple.',
            'provider_id.required' => 'Provider ID from social provider is required.',
            'country_id.exists' => 'The selected country is invalid.',
        ];
    }
}

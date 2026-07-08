<?php

namespace App\Http\Requests;

use App\Enums\DocumentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'eoi_id' => ['nullable', 'integer', 'exists:eois,id'],
            'document_type' => ['required', new Enum(DocumentType::class)],
            'document_name' => ['required', 'string', 'max:255'],
            'expiry_date' => ['nullable', 'date'],
            'issue_date' => ['nullable', 'date'],
            'attachment_url' => ['nullable', 'string', 'max:2048'],
            'reminder_days' => ['nullable', 'integer', 'min:1', 'max:365'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'document_type.required' => 'Document type is required.',
            'document_name.required' => 'Document name is required.',
            'eoi_id.exists' => 'Invalid EOI reference.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEoiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'eoi_number' => ['required', 'string', 'max:255'],
            'submission_date' => ['required', 'date'],
            'subclass_id' => ['nullable', 'integer', 'exists:visa_subclasses,id'],
            'responses' => ['required', 'array'],
            'responses.*.question_id' => ['required', 'integer', 'exists:eoi_questions,id'],
            'responses.*.answer_id' => ['required', 'integer', 'exists:eoi_answers,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'eoi_number.required' => 'EOI number is required.',
            'submission_date.required' => 'Submission date is required.',
            'submission_date.date' => 'Submission date must be a valid date.',
            'subclass_id.exists' => 'Invalid visa subclass.',
            'responses.required' => 'Please provide your responses to the EOI questions.',
            'responses.array' => 'Responses must be provided as an array.',
            'responses.*.question_id.required' => 'Question ID is required for each response.',
            'responses.*.question_id.exists' => 'Invalid question ID provided.',
            'responses.*.answer_id.required' => 'Answer ID is required for each response.',
            'responses.*.answer_id.exists' => 'Invalid answer ID provided.',
        ];
    }
}

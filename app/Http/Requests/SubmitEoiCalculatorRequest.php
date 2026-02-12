<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitEoiCalculatorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'responses' => 'required|array',
            'responses.*.question_id' => 'required|integer|exists:eoi_questions,id',
            'responses.*.answer_id' => 'required|integer|exists:eoi_answers,id',
        ];
    }

    public function messages(): array
    {
        return [
            'responses.required' => 'Please provide your responses to the EOI questions.',
            'responses.array' => 'Responses must be provided as an array.',
            'responses.*.question_id.required' => 'Question ID is required for each response.',
            'responses.*.question_id.exists' => 'Invalid question ID provided.',
            'responses.*.answer_id.required' => 'Answer ID is required for each response.',
            'responses.*.answer_id.exists' => 'Invalid answer ID provided.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitOnboardingAnswersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'answers' => ['required', 'array', 'min:1'],
            'answers.*.question_id' => ['required', 'integer', 'exists:onboarding_questions,id'],
            'answers.*.option_id' => ['nullable', 'integer', 'exists:onboarding_question_options,id'],
            'answers.*.answer_date' => ['nullable', 'date'],
            'answers.*.country_id' => ['nullable', 'integer', 'exists:countries,id'],
            'answers.*.state_ids' => ['nullable', 'array'],
            'answers.*.state_ids.*' => ['integer', 'exists:states,id'],
            'answers.*.occupation_list_ids' => ['nullable', 'array'],
            'answers.*.occupation_list_ids.*' => ['integer', 'exists:occupation_lists,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'answers.required' => 'At least one answer is required.',
            'answers.*.question_id.required' => 'A question ID is required for each answer.',
            'answers.*.question_id.exists' => 'One or more question IDs are invalid.',
            'answers.*.option_id.exists' => 'One or more option IDs are invalid.',
            'answers.*.answer_date.date' => 'The answer date must be a valid date.',
            'answers.*.country_id.exists' => 'One or more country IDs are invalid.',
            'answers.*.state_ids.*.exists' => 'One or more state IDs are invalid.',
            'answers.*.occupation_list_ids.*.exists' => 'One or more occupation list IDs are invalid.',
        ];
    }
}

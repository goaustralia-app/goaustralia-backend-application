<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OnboardingQuestionResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'question' => $this->question,
            'answer_type' => $this->answer_type,
            'input_type' => $this->input_type,
            'sort_order' => $this->sort_order,
            'condition' => $this->condition_question_id ? [
                'question_id' => $this->condition_question_id,
                'option_id' => $this->condition_option_id,
            ] : null,
            'options' => $this->whenLoaded('options', fn () => $this->options->map(fn ($option) => [
                'id' => $option->id,
                'option_text' => $option->option_text,
            ])->values()->all()),
        ];
    }
}

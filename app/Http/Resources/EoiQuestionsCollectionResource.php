<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EoiQuestionsCollectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'questions' => EoiQuestionResource::collection($this->resource),
            'metadata' => [
                'total_questions' => $this->resource->count(),
                'categories' => $this->getCategories(),
                'instructions' => [
                    'Select one answer for each question',
                    'Points are awarded based on your selections',
                    'Visa subclass selection affects final points calculation',
                    'Minimum 65 points required for eligibility',
                ],
                'visa_subclass_bonuses' => [
                    '189' => ['name' => 'Skilled Independent', 'bonus_points' => 0],
                    '190' => ['name' => 'Skilled Nominated', 'bonus_points' => 5],
                    '491' => ['name' => 'Skilled Work Regional', 'bonus_points' => 15],
                ],
            ],
        ];
    }

    private function getCategories(): array
    {
        return $this->resource->groupBy('category')
            ->map(function ($questions, $category) {
                return [
                    'name' => $category,
                    'question_count' => $questions->count(),
                    'max_points' => $questions->flatMap->answers->max('points') ?? 0,
                ];
            })
            ->values()
            ->toArray();
    }
}

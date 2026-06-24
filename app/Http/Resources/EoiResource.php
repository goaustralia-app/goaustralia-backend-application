<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EoiResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'eoi_number' => $this->eoi_number,
            'submission_date' => $this->submission_date?->toDateString(),
            'total_points' => $this->total_points,
            'subclass' => $this->whenLoaded('subclass', fn () => [
                'id' => $this->subclass->id,
                'subclass_code' => $this->subclass->subclass_code,
                'name' => $this->subclass->name,
            ]),
            'details' => $this->whenLoaded('details', fn () => $this->details->map(fn ($detail) => [
                'id' => $detail->id,
                'category' => $detail->question->category,
                'question' => $detail->question->question,
                'answer' => $detail->answer->answer_text,
                'points' => $detail->points,
            ])),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

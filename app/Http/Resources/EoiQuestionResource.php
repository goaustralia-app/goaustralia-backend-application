<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EoiQuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'category' => $this->category,
            'question' => $this->question,
            'description' => $this->description,
            'order_position' => $this->order_position,
            'answers' => EoiAnswerResource::collection($this->whenLoaded('answers')),
        ];
    }
}

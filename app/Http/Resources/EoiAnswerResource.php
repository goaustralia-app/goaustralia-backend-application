<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EoiAnswerResource extends JsonResource
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
            'answer_text' => $this->answer_text,
            'points' => $this->points,
            'description' => $this->description,
            'order_position' => $this->order_position,
        ];
    }
}

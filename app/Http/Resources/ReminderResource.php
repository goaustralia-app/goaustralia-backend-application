<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReminderResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reminder_type' => $this->reminder_type->value,
            'reference_id' => $this->reference_id,
            'reminder_date' => $this->reminder_date?->toDateString(),
            'status' => $this->status->value,
            'sent_at' => $this->sent_at,
            'created_at' => $this->created_at,
        ];
    }
}

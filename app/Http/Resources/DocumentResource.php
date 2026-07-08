<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'eoi_id' => $this->eoi_id,
            'document_type' => $this->document_type->value,
            'document_name' => $this->document_name,
            'expiry_date' => $this->expiry_date?->toDateString(),
            'issue_date' => $this->issue_date?->toDateString(),
            'attachment_url' => $this->attachment_url,
            'reminder_days' => $this->reminder_days,
            'notes' => $this->notes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

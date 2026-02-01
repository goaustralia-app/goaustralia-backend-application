<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VisaSubclassResource extends JsonResource
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
            'subclass_code' => $this->subclass_code,
            'name' => $this->name,
            'slug' => $this->slug,
            // 'stream' => $this->stream,
            'description' => $this->description,
            'status' => $this->status,
            'is_permanent' => $this->is_permanent,
            // 'is_provisional' => $this->is_provisional,
            // 'validity_months' => $this->validity_months,
            // 'pathway_to_pr' => $this->pathway_to_pr,
            // 'points_tested' => $this->points_tested,
            // 'min_points_required' => $this->min_points_required,
            // 'requires_nomination' => $this->requires_nomination,
            // 'requires_sponsorship' => $this->requires_sponsorship,
            // 'annual_cap' => $this->annual_cap,
            // 'last_policy_update' => $this->last_policy_update,
            // 'processing_time_months' => $this->processing_time_months,
            // 'visa_cost_aud' => $this->visa_cost_aud,
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StatesSkilledOccupationListResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'state' => $this->state,
            'anzsco_code' => $this->anzsco_code,
            'occupation' => $this->occupation,
            'subclass_491_eligible' => $this->subclass_491_eligible,
            'subclass_190_eligible' => $this->subclass_190_eligible,
            'additional_information' => $this->additional_information,
            'category' => $this->category,
            'financial_year' => $this->financial_year,
            'subclass' => $this->whenLoaded('subclass', fn () => [
                'id' => $this->subclass->id,
                'subclass_code' => $this->subclass->subclass_code,
                'name' => $this->subclass->name,
            ]),
            'state_relation' => $this->whenLoaded('stateRelation', fn () => $this->stateRelation ? [
                'id' => $this->stateRelation->id,
                'name' => $this->stateRelation->name,
                'code' => $this->stateRelation->code,
            ] : null),
        ];
    }
}

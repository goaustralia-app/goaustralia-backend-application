<?php

namespace App\Http\Resources;

use App\Models\StatesSkilledOccupationList;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StateWithOccupationListResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'occupations' => $this->whenLoaded('skilledOccupationLists', fn () => $this->skilledOccupationLists->map(fn (StatesSkilledOccupationList $occupation) => [
                'id' => $occupation->id,
                'anzsco_code' => $occupation->anzsco_code,
                'occupation' => $occupation->occupation,
                'subclass_491_eligible' => $occupation->subclass_491_eligible,
                'subclass_190_eligible' => $occupation->subclass_190_eligible,
                'additional_information' => $occupation->additional_information,
                'category' => $occupation->category,
                'financial_year' => $occupation->financial_year,
                // 'subclass' => $occupation->subclass ? [
                //     'id' => $occupation->subclass->id,
                //     'subclass_code' => $occupation->subclass->subclass_code,
                //     'name' => $occupation->subclass->name,
                // ] : null,
            ])->values()->all()
            ),
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatesSkilledOccupationList extends Model
{
    /** @use HasFactory<\Database\Factories\StatesSkilledOccupationListFactory> */
    use HasFactory;

    protected $fillable = [
        'subclass_id',
        'state_id',
        'state',
        'anzsco_code',
        'occupation',
        'subclass_491_eligible',
        'subclass_190_eligible',
        'additional_information',
        'category',
        'financial_year',
    ];

    protected function casts(): array
    {
        return [
            'subclass_491_eligible' => 'boolean',
            'subclass_190_eligible' => 'boolean',
        ];
    }

    public function subclass(): BelongsTo
    {
        return $this->belongsTo(VisaSubclass::class, 'subclass_id');
    }

    public function stateRelation(): BelongsTo
    {
        return $this->belongsTo(State::class, 'state_id');
    }
}

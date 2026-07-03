<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class State extends Model
{
    protected $fillable = [
        'name',
        'code',
        'capital',
        'timezone',
        'region_type',
        'is_regional',
        'regional_class',
        'migration_priority',
        'state_nomination',
    ];

    protected function casts(): array
    {
        return [
            'is_regional' => 'boolean',
            'state_nomination' => 'boolean',
            'migration_priority' => 'integer',
        ];
    }

    public function skilledOccupationLists(): HasMany
    {
        return $this->hasMany(StatesSkilledOccupationList::class);
    }
}

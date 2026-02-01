<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OccupationList extends Model
{
    protected $fillable = [
        'occupation',
        'anzsco_code',
        'assessing_authority',
        'list',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}

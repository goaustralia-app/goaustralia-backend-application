<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisaSubclass extends Model
{
    protected $fillable = [
        'subclass_code',
        'name',
        'slug',
        'stream',
        'description',
        'status',
        'is_permanent',
        'is_provisional',
        'validity_months',
        'pathway_to_pr',
        'points_tested',
        'min_points_required',
        'requires_nomination',
        'requires_sponsorship',
        'annual_cap',
        'last_policy_update',
        'processing_time_months',
        'visa_cost_aud',
    ];

    protected function casts(): array
    {
        return [
            'is_permanent' => 'boolean',
            'is_provisional' => 'boolean',
            'pathway_to_pr' => 'boolean',
            'points_tested' => 'boolean',
            'requires_nomination' => 'boolean',
            'requires_sponsorship' => 'boolean',
            'last_policy_update' => 'date',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}

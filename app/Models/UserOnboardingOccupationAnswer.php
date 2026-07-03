<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserOnboardingOccupationAnswer extends Model
{
    protected $fillable = [
        'user_id',
        'occupation_list_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function occupationList(): BelongsTo
    {
        return $this->belongsTo(OccupationList::class, 'occupation_list_id');
    }
}

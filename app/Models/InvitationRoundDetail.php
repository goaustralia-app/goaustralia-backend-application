<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvitationRoundDetail extends Model
{
    protected $fillable = [
        'invitation_round_id',
        'occupation_id',
        'occupation',
        'anzsco_code',
        'points',
        'eoi_count',
    ];

    protected function casts(): array
    {
        return [
            'points' => 'integer',
            'eoi_count' => 'integer',
        ];
    }

    public function invitationRound(): BelongsTo
    {
        return $this->belongsTo(InvitationRound::class);
    }

    public function occupationList(): BelongsTo
    {
        return $this->belongsTo(OccupationList::class, 'occupation_id');
    }
}

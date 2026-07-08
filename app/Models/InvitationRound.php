<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InvitationRound extends Model
{
    use HasFactory;

    protected $fillable = [
        'subclass_id',
        'round_date',
        'number_of_invitations',
        'number_of_applications',
        'minimum_points',
        'tie_break_date',
    ];

    protected function casts(): array
    {
        return [
            'round_date' => 'date',
            'tie_break_date' => 'datetime',
            'number_of_invitations' => 'integer',
            'number_of_applications' => 'integer',
            'minimum_points' => 'integer',
        ];
    }

    public function subclass(): BelongsTo
    {
        return $this->belongsTo(VisaSubclass::class, 'subclass_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(InvitationRoundDetail::class);
    }
}

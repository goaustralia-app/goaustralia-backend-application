<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Eoi extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'eoi_number',
        'submission_date',
        'expiry_date',
        'state_nomination',
        'notes',
        'total_points',
        'subclass_id',
    ];

    protected function casts(): array
    {
        return [
            'submission_date' => 'date',
            'expiry_date' => 'date',
            'total_points' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subclass(): BelongsTo
    {
        return $this->belongsTo(VisaSubclass::class, 'subclass_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(EoiDetail::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(EoiHistory::class);
    }
}

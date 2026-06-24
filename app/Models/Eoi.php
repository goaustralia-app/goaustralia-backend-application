<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Eoi extends Model
{
    protected $fillable = [
        'user_id',
        'eoi_number',
        'submission_date',
        'total_points',
        'subclass_id',
    ];

    protected function casts(): array
    {
        return [
            'submission_date' => 'date',
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
}

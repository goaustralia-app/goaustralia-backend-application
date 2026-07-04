<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EoiAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'eoi_question_id',
        'answer_text',
        'points',
        'description',
        'order_position',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'points' => 'integer',
        ];
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(EoiQuestion::class, 'eoi_question_id');
    }

    public function userResponses(): HasMany
    {
        return $this->hasMany(EoiUserResponse::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order_position');
    }
}

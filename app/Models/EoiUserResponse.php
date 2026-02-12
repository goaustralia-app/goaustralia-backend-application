<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EoiUserResponse extends Model
{
    protected $fillable = [
        'user_id',
        'eoi_question_id',
        'eoi_answer_id',
        'points',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(EoiQuestion::class, 'eoi_question_id');
    }

    public function answer(): BelongsTo
    {
        return $this->belongsTo(EoiAnswer::class, 'eoi_answer_id');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    protected function casts(): array
    {
        return [
            'points' => 'integer',
        ];
    }
}

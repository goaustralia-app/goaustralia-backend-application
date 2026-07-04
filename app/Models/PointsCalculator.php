<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PointsCalculator extends Model
{
    use HasFactory;

    protected $table = 'points_calculator';

    protected $fillable = [
        'user_id',
        'eoi_question_id',
        'eoi_answer_id',
        'points',
    ];

    protected function casts(): array
    {
        return [
            'points' => 'integer',
        ];
    }

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
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EoiDetail extends Model
{
    protected $fillable = [
        'eoi_id',
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

    public function eoi(): BelongsTo
    {
        return $this->belongsTo(Eoi::class);
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

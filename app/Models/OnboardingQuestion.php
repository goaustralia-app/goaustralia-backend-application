<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OnboardingQuestion extends Model
{
    protected $fillable = [
        'question',
        'answer_type',
        'input_type',
        'sort_order',
        'condition_question_id',
        'condition_option_id',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'condition_question_id' => 'integer',
            'condition_option_id' => 'integer',
        ];
    }

    public function options(): HasMany
    {
        return $this->hasMany(OnboardingQuestionOption::class, 'question_id')->orderBy('sort_order');
    }

    public function conditionQuestion(): BelongsTo
    {
        return $this->belongsTo(OnboardingQuestion::class, 'condition_question_id');
    }

    public function conditionOption(): BelongsTo
    {
        return $this->belongsTo(OnboardingQuestionOption::class, 'condition_option_id');
    }
}

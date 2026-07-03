<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserOnboardingAnswer extends Model
{
    protected $fillable = [
        'user_id',
        'question_id',
        'option_id',
        'answer_date',
        'country_id',
    ];

    protected function casts(): array
    {
        return [
            'answer_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(OnboardingQuestion::class, 'question_id');
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(OnboardingQuestionOption::class, 'option_id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}

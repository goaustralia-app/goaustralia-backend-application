<?php

namespace Database\Factories;

use App\Models\EoiAnswer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PointsCalculator>
 */
class PointsCalculatorFactory extends Factory
{
    public function definition(): array
    {
        $answer = EoiAnswer::factory()->create();

        return [
            'user_id' => User::factory(),
            'eoi_question_id' => $answer->eoi_question_id,
            'eoi_answer_id' => $answer->id,
            'points' => $answer->points,
        ];
    }
}

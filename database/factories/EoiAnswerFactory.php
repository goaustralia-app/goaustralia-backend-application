<?php

namespace Database\Factories;

use App\Models\EoiQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EoiAnswer>
 */
class EoiAnswerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'eoi_question_id' => EoiQuestion::factory(),
            'answer_text' => $this->faker->sentence(),
            'points' => $this->faker->numberBetween(0, 20),
            'description' => $this->faker->optional()->sentence(),
            'order_position' => $this->faker->numberBetween(1, 10),
            'is_active' => true,
        ];
    }
}

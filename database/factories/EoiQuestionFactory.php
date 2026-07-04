<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EoiQuestion>
 */
class EoiQuestionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category' => $this->faker->randomElement([
                'English Language',
                'Age',
                'Education',
                'Work Experience (In Australia)',
                'Work Experience (Outside Australia)',
                'Professional Year',
            ]),
            'question' => $this->faker->sentence(),
            'description' => $this->faker->optional()->sentence(),
            'order_position' => $this->faker->numberBetween(1, 20),
            'is_active' => true,
        ];
    }
}

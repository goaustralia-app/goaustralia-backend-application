<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Eoi>
 */
class EoiFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'eoi_number' => 'EOI-'.$this->faker->unique()->numerify('####'),
            'submission_date' => $this->faker->dateTimeBetween('-2 years', '-1 month')->format('Y-m-d'),
            'total_points' => $this->faker->numberBetween(60, 100),
        ];
    }
}

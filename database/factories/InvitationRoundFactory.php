<?php

namespace Database\Factories;

use App\Models\VisaSubclass;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InvitationRound>
 */
class InvitationRoundFactory extends Factory
{
    public function definition(): array
    {
        return [
            'subclass_id' => VisaSubclass::factory(),
            'round_date' => $this->faker->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
            'number_of_invitations' => $this->faker->numberBetween(100, 5000),
            'minimum_points' => $this->faker->numberBetween(65, 100),
        ];
    }
}

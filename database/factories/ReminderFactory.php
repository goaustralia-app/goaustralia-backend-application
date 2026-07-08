<?php

namespace Database\Factories;

use App\Enums\ReminderStatus;
use App\Enums\ReminderType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reminder>
 */
class ReminderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'reminder_type' => $this->faker->randomElement(ReminderType::cases())->value,
            'reference_id' => $this->faker->numberBetween(1, 100),
            'reminder_date' => $this->faker->dateTimeBetween('now', '+60 days')->format('Y-m-d'),
            'status' => ReminderStatus::Pending->value,
        ];
    }
}

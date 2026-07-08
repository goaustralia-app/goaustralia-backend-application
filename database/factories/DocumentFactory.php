<?php

namespace Database\Factories;

use App\Enums\DocumentType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Document>
 */
class DocumentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'eoi_id' => null,
            'document_type' => $this->faker->randomElement(DocumentType::cases())->value,
            'document_name' => $this->faker->words(3, true),
            'expiry_date' => $this->faker->optional()->dateTimeBetween('+1 month', '+5 years')?->format('Y-m-d'),
            'issue_date' => $this->faker->optional()->dateTimeBetween('-5 years', 'now')?->format('Y-m-d'),
            'reminder_days' => 30,
        ];
    }
}

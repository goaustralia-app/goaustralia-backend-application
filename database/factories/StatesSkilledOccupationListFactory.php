<?php

namespace Database\Factories;

use App\Models\VisaSubclass;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StatesSkilledOccupationList>
 */
class StatesSkilledOccupationListFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'subclass_id' => VisaSubclass::factory(),
            'state_id' => null,
            'state' => fake()->randomElement(['NSW', 'VIC', 'QLD', 'SA', 'WA', 'TAS', 'NT', 'ACT']),
            'anzsco_code' => fake()->numerify('######'),
            'occupation' => fake()->jobTitle(),
            'subclass_491_eligible' => fake()->boolean(),
            'subclass_190_eligible' => fake()->boolean(),
            'additional_information' => fake()->optional()->sentence(),
            'category' => fake()->randomElement(['onshore', 'offshore']),
            'financial_year' => '2026-2027',
        ];
    }
}

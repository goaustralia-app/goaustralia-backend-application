<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VisaSubclass>
 */
class VisaSubclassFactory extends Factory
{
    public function definition(): array
    {
        $code = $this->faker->unique()->numberBetween(100, 999);

        return [
            'subclass_code' => (string) $code,
            'name' => 'Skilled '.$this->faker->word(),
            'slug' => Str::slug('skilled-'.$code),
            'status' => 1,
            'is_permanent' => false,
            'is_provisional' => false,
            'pathway_to_pr' => false,
            'points_tested' => true,
            'requires_nomination' => false,
            'requires_sponsorship' => false,
        ];
    }
}

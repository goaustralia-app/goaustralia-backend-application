<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VisaSubclassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $visaSubclasses = [
            [
                'subclass_code' => '189',
                'name' => 'Skilled Independent visa',
                'slug' => 'skilled-independent-189',
                'stream' => null,
                'description' => 'A permanent visa for invited skilled workers to live and work anywhere in Australia.',
                'is_permanent' => true,
                'is_provisional' => false,
                'validity_months' => null,
                'pathway_to_pr' => false,
                'points_tested' => true,
                'min_points_required' => 65,
                'requires_nomination' => false,
                'requires_sponsorship' => false,
                'annual_cap' => null,
                'last_policy_update' => null,
                'processing_time_months' => null,
                'visa_cost_aud' => 4910,
            ],
            [
                'subclass_code' => '190',
                'name' => 'Skilled Nominated visa',
                'slug' => 'skilled-nominated-190',
                'stream' => null,
                'description' => 'A permanent visa for invited skilled workers nominated by an Australian state or territory.',
                'is_permanent' => true,
                'is_provisional' => false,
                'validity_months' => null,
                'pathway_to_pr' => false,
                'points_tested' => true,
                'min_points_required' => 65,
                'requires_nomination' => true,
                'requires_sponsorship' => false,
                'annual_cap' => null,
                'last_policy_update' => null,
                'processing_time_months' => null,
                'visa_cost_aud' => 4910,
            ],
            [
                'subclass_code' => '491',
                'name' => 'Skilled Work Regional (Provisional) visa',
                'slug' => 'skilled-work-regional-provisional-491',
                'stream' => null,
                'description' => 'A provisional visa for invited skilled workers nominated by a state/territory or sponsored by an eligible relative to live and work in regional Australia.',
                'is_permanent' => false,
                'is_provisional' => true,
                'validity_months' => 60,
                'pathway_to_pr' => true,
                'points_tested' => true,
                'min_points_required' => 65,
                'requires_nomination' => true,
                'requires_sponsorship' => true,
                'annual_cap' => null,
                'last_policy_update' => null,
                'processing_time_months' => null,
                'visa_cost_aud' => 4910,
            ],
        ];

        foreach ($visaSubclasses as $visaSubclass) {
            DB::table('visa_subclasses')->updateOrInsert(
                ['subclass_code' => $visaSubclass['subclass_code']],
                array_merge($visaSubclass, [
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}

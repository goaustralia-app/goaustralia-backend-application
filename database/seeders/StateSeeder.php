<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $timezoneSydney = 'Australia/Sydney';

        $states = [
            [
                'name' => 'New South Wales',
                'code' => 'NSW',
                'capital' => 'Sydney',
                'timezone' => $timezoneSydney,
                'region_type' => 'state',
            ],
            [
                'name' => 'Victoria',
                'code' => 'VIC',
                'capital' => 'Melbourne',
                'timezone' => $timezoneSydney,
                'region_type' => 'state',
            ],
            [
                'name' => 'Queensland',
                'code' => 'QLD',
                'capital' => 'Brisbane',
                'timezone' => 'Australia/Brisbane',
                'region_type' => 'state',
            ],
            [
                'name' => 'Western Australia',
                'code' => 'WA',
                'capital' => 'Perth',
                'timezone' => 'Australia/Perth',
                'region_type' => 'state',
            ],
            [
                'name' => 'South Australia',
                'code' => 'SA',
                'capital' => 'Adelaide',
                'timezone' => 'Australia/Adelaide',
                'region_type' => 'state',
            ],
            [
                'name' => 'Tasmania',
                'code' => 'TAS',
                'capital' => 'Hobart',
                'timezone' => $timezoneSydney,
                'region_type' => 'state',
            ],
            [
                'name' => 'Australian Capital Territory',
                'code' => 'ACT',
                'capital' => 'Canberra',
                'timezone' => $timezoneSydney,
                'region_type' => 'territory',
            ],
            [
                'name' => 'Northern Territory',
                'code' => 'NT',
                'capital' => 'Darwin',
                'timezone' => 'Australia/Darwin',
                'region_type' => 'territory',
            ],
        ];

        foreach ($states as $state) {
            DB::table('states')->updateOrInsert(
                ['code' => $state['code']],
                array_merge($state, [
                    'is_regional' => false,
                    'regional_class' => 'mixed',
                    'migration_priority' => 0,
                    'state_nomination' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}

<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SkilledOccupationListSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('data/skilled_occupation_list.csv');

        if (! File::exists($path)) {
            throw new \RuntimeException("CSV file not found at {$path}");
        }

        $rows = array_map('str_getcsv', file($path));
        $header = array_map('trim', array_shift($rows));

        $now = Carbon::now();
        $data = [];

        foreach ($rows as $row) {
            if (count($row) !== count($header)) {
                continue;
            }

            $row = array_combine($header, $row);
            $data[] = [
                'occupation' => trim($row['occupation']),
                'anzsco_code' => trim($row['anzsco_code']),
                'assessing_authority' => trim($row['assessing_authority']) ?: null,
                'list' => trim($row['list']),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('occupation_lists')->truncate();
        DB::table('occupation_lists')->insert($data);
    }
}

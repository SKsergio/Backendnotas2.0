<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\catalogues\Periods;

class PeriodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Periods::create([
            'code' => 'PRD001',
            'name' => 'Periodo 1',
            'year' => '2025',
            'date_from' => '2025-01-10',
            'date_to' => '2025-04-',
        ]);
    }
}

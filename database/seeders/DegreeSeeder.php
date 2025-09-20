<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\catalogues\Degree;

class DegreeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Degree::create([
            'code' => 'grd01',
            'name' => 'Pirmer Grado'
        ]);

        Degree::create([
            'code' => 'MED',
            'name' => 'Medicina'
        ]);

        Degree::create([
            'code' => '4tFDD',
            'name' => 'cUARTO gRADO'
        ]);

        Degree::create([
            'code' => 'FRD',
            'name' => 'Administración'
        ]);

        Degree::create([
            'code' => 'DGDF',
            'name' => 'Administración'
        ]);

        Degree::create([
            'code' => '134FGD',
            'name' => 'Administración'
        ]);
    }
}

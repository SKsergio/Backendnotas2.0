<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\catalogues\Sections;

class SectionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Sections::create([
            'code' => 'SEC1',
            'name' => 'seccion'
        ]);

        Sections::create([
            'code' => 'SEC2',
            'name' => 'seccion 1'
        ]);

        Sections::create([
            'code' => 'SEC3',
            'name' => 'sectryt 76'
        ]);

        Sections::create([
            'code' => 'SEC4',
            'name' => 'dxjcghe 1'
        ]);

        Sections::create([
            'code' => 'SEC5',
            'name' => 'df 1'
        ]);

        Sections::create([
            'code' => 'SEC6',
            'name' => 'secc33'
        ]);
    }
}

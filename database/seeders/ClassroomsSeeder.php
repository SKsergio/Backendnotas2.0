<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\catalogues\Classrooms;

class ClassroomsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Classrooms::create([
            'code' => 'GRD001',
            'name' => 'seccion'
        ]);

        Classrooms::create([
            'code' => 'GRD002',
            'name' => 'seccion 1'
        ]);

        Classrooms::create([
            'code' => 'GRD003',
            'name' => 'sectryt 76'
        ]);

        Classrooms::create([
            'code' => 'GRD004',
            'name' => 'dxjcghe 1'
        ]);
    }
}

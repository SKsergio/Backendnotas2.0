<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\catalogues\TypeFile;

class TypeFileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TypeFile::create([
            'code' => 'FTP',
            'name' => 'Foto de perfil'
        ]);

        TypeFile::create([
            'code' => 'COMP',
            'name' => 'Comprobante de pago'
        ]);

        TypeFile::create([
            'code' => 'CONST',
            'name' => 'Constancia de estudio'
        ]);

        TypeFile::create([
            'code' => 'FICH',
            'name' => 'FICHA'
        ]);

        TypeFile::create([
            'code' => 'CERT',
            'name' => 'Certificado'
        ]);

        TypeFile::create([
            'code' => 'DEIP',
            'name' => 'Diploma'
        ]);
    }
}

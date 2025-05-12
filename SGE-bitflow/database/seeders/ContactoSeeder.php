<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contacto1 = \App\Models\Contacto::firstOrCreate([
            'nombre_contacto' => 'Juan Pérez',
            'telefono_contacto' => '123456789',
            'email_contacto' => 'correofalso123@gmail.com',
            'cliente_id' => 1,
            'tipo_contacto' => 'Comercial',
        ]);
        $contacto2 = \App\Models\Contacto::firstOrCreate([
            'nombre_contacto' => 'María González',
            'telefono_contacto' => '987654321',
            'email_contacto' => 'correofalso1234@gmail.com',
            'cliente_id' => 1,
            'tipo_contacto' => 'TI',
        ]);
        $contacto3 = \App\Models\Contacto::firstOrCreate([
            'nombre_contacto' => 'Pedro Fernández',
            'telefono_contacto' => '456789123',
            'email_contacto' => 'correofalso1235@gmail.com',
            'cliente_id' => 2,
            'tipo_contacto' => 'Contable',
        ]);
    }
}

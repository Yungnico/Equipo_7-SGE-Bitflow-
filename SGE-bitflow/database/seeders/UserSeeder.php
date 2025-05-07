<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Busca al usuario registrado
        $user = User::where('email', 'admin@admin.com')->first();

        if ($user) {
            // Asigna el rol al usuario
            $user->assignRole('Admin'); // Cambia 'Admin' por el nombre del rol que desees asignar
        }
    }
}

<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call(UserSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(ClienteSeeder::class);
        $this->call(CategoriaSeeder::class);
        $this->call(ServicioSeeder::class);
        $this->call(ContactoSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(CategoriasCostosSeeder::class);
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}

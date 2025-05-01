<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role1 = Role::create(['name' =>'Admin']);
        $role2 = Role::create(['name' =>'Comercial']);

        Permission::create(['name' => 'dashboard'])->syncRoles([$role1, $role2]);

        Permission::create(['name' => 'servicios.index']);
        Permission::create(['name' => 'servicios.create']);
        Permission::create(['name' => 'servicios.edit']);
        Permission::create(['name' => 'servicios.destroy']);

        Permission::create(['name' => 'perfil.index']);
        Permission::create(['name' => 'perfil.edit']);
        Permission::create(['name' => 'perfil.destroy']);

        Permission::create(['name' => 'cambioContraseña.index']);
        Permission::create(['name' => 'cambioContraseña.edit']);
    }
}

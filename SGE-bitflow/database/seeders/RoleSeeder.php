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

        Permission::create(['name' => 'servicios.index'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'servicios.create'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'servicios.edit'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'servicios.destroy'])->syncRoles([$role1, $role2]);

        Permission::create(['name' => 'perfil.index'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'perfil.edit'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'perfil.destroy'])->syncRoles([$role1, $role2]);

        Permission::create(['name' => 'cambioContraseña.index'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'cambioContraseña.edit'])->syncRoles([$role1, $role2]);
        
    }
}

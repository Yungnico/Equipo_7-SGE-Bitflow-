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
        $role1 = Role::firstOrCreate(['name' => 'Admin']);
        $role2 = Role::firstOrCreate(['name' => 'Comercial']);

        Permission::firstOrCreate(['name' => 'dashboard'])->syncRoles([$role1, $role2]);

        Permission::firstOrCreate(['name' => 'perfil.index'])->syncRoles([$role1, $role2]);
        Permission::firstOrCreate(['name' => 'perfil.edit'])->syncRoles([$role1, $role2]);
        Permission::firstOrCreate(['name' => 'perfil.destroy'])->syncRoles([$role1, $role2]);

        Permission::firstOrCreate(['name' => 'cambioContraseña.index'])->syncRoles([$role1, $role2]);
        Permission::firstOrCreate(['name' => 'cambioContraseña.edit'])->syncRoles([$role1, $role2]);

        Permission::firstOrCreate(['name' => 'admin.viewusers.index'])->syncRoles([$role1]);
        Permission::firstOrCreate(['name' => 'admin.viewusers.edit'])->syncRoles([$role1]);//tengo que poner middleware en la ruta de editar usuario
        Permission::firstOrCreate(['name' => 'admin.viewusers.update'])->syncRoles([$role1]);

        Permission::firstOrCreate(['name' => 'user.create'])->syncRoles([$role1]);

        Permission::firstOrCreate(['name' => 'servicios.index'])->syncRoles([$role1, $role2]);
        Permission::firstOrCreate(['name' => 'servicios.create'])->syncRoles([$role1, $role2]);
        Permission::firstOrCreate(['name' => 'servicios.edit'])->syncRoles([$role1, $role2]);
        Permission::firstOrCreate(['name' => 'servicios.destroy'])->syncRoles([$role1, $role2]);



        
    }
}

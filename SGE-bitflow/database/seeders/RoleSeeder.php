<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

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

        Permission::firstOrCreate(['name' => 'perfil.index'])->syncRoles([$role1, $role2]);//creo que no se usara
        Permission::firstOrCreate(['name' => 'perfil.edit'])->syncRoles([$role1, $role2]); //creo que no se usara
        Permission::firstOrCreate(['name' => 'perfil.destroy'])->syncRoles([$role1, $role2]);//creo que no se usara

        Permission::firstOrCreate(['name' => 'cambioContraseña.index'])->syncRoles([$role1, $role2]);//creo que no se usara
        Permission::firstOrCreate(['name' => 'cambioContraseña.edit'])->syncRoles([$role1, $role2]);//creo que no se usara

        Permission::firstOrCreate(['name' => 'admin.viewusers.index'])->syncRoles([$role1]);//middleware puesto
        Permission::firstOrCreate(['name' => 'admin.viewusers.edit'])->syncRoles([$role1]);
        Permission::firstOrCreate(['name' => 'admin.viewusers.update'])->syncRoles([$role1]);

        Permission::firstOrCreate(['name' => 'user.create'])->syncRoles([$role1]);//middleware puesto

        Permission::firstOrCreate(['name' => 'cliente.index'])->syncRoles([$role1, $role2]);
        Permission::firstOrCreate(['name' => 'cliente.create'])->syncRoles([$role1, $role2]);
        Permission::firstOrCreate(['name' => 'cliente.edit'])->syncRoles([$role1, $role2]);
        Permission::firstOrCreate(['name' => 'cliente.destroy'])->syncRoles([$role1, $role2]);

        Permission::firstOrCreate(['name' => 'contactoCliente.index'])->syncRoles([$role1, $role2]);
        Permission::firstOrCreate(['name' => 'contactoCliente.create'])->syncRoles([$role1, $role2]);
        Permission::firstOrCreate(['name' => 'contactoCliente.edit'])->syncRoles([$role1, $role2]);
        Permission::firstOrCreate(['name' => 'contactoCliente.destroy'])->syncRoles([$role1, $role2]);



        Permission::firstOrCreate(['name' => 'servicios.index'])->syncRoles([$role1, $role2]);
        Permission::firstOrCreate(['name' => 'servicios.create'])->syncRoles([$role1, $role2]);
        Permission::firstOrCreate(['name' => 'servicios.edit'])->syncRoles([$role1, $role2]);
        Permission::firstOrCreate(['name' => 'servicios.destroy'])->syncRoles([$role1, $role2]);

        $adminUser = User::where('email', 'admin@admin.com')->first();
        if ($adminUser) {
            $adminUser->assignRole($role1);
        }



        
    }
}

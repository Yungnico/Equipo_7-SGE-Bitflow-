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

        Permission::firstOrCreate(['name' => 'cotizaciones.menu'])->syncRoles([$role1, $role2]);//middleware puesto
        Permission::firstOrCreate(['name' => 'cotizaciones.create'])->syncRoles([$role1, $role2]);//middleware puesto
        Permission::firstOrCreate(['name' => 'cotizaciones.index'])->syncRoles([$role1, $role2]);//middleware puesto
        Permission::firstOrCreate(['name' => 'cotizaciones.borrador'])->syncRoles([$role1, $role2]);//middleware puesto
        Permission::firstOrCreate(['name' => 'cotizaciones.edit'])->syncRoles([$role1, $role2]);//middleware puesto
        Permission::firstOrCreate(['name' => 'cotizaciones.prepararpdf'])->syncRoles([$role1, $role2]);//middleware puesto
        Permission::firstOrCreate(['name' => 'cotizaciones.email'])->syncRoles([$role1, $role2]);//middleware puesto
        Permission::firstOrCreate(['name' => 'cotizaciones.info'])->syncRoles([$role1, $role2]);//middleware puesto


        Permission::firstOrCreate(['name' => 'admin.viewusers.index'])->syncRoles([$role1]);//middleware puesto
        Permission::firstOrCreate(['name' => 'admin.viewusers.edit'])->syncRoles([$role1]);//middleware puesto

        Permission::firstOrCreate(['name' => 'user.create'])->syncRoles([$role1]);//middleware puesto

        Permission::firstOrCreate(['name' => 'cliente.index'])->syncRoles([$role1, $role2]);
        Permission::firstOrCreate(['name' => 'cliente.contacto'])->syncRoles([$role1, $role2]);

        Permission::firstOrCreate(['name' => 'contactoCliente.index'])->syncRoles([$role1, $role2]);
        Permission::firstOrCreate(['name' => 'contactoCliente.edit'])->syncRoles([$role1, $role2]);
        Permission::firstOrCreate(['name' => 'contactoCliente.create'])->syncRoles([$role1, $role2]);




        Permission::firstOrCreate(['name' => 'servicios.index'])->syncRoles([$role1, $role2]);


        $adminUser = User::where('email', 'admin@admin.com')->first();
        if ($adminUser) {
            $adminUser->assignRole($role1);
        }



        
    }
}

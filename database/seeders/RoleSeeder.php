<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Se utiliza el modelos Role y se le asigna el nombre del Rol
        $admin = Role::create(['name'=> 'Admin']);
        $empleado = Role::create(['name'=> 'empleado']);

        $cliente = Role::create(['name'=> 'cliente']);
        
        /*se utiliza el metodo syncRoles para sincronizar varios permisos a un  roles o varios roles
        ->syncRoles([$admin,$empleado]); */

        Permission::create(['name'=>'panel.home'])->syncRoles([$admin,$empleado]);
        Permission::create(['name'=>'productos.index'])->syncRoles([$admin,$empleado]);
        Permission::create(['name'=>'productos.create'])->syncRoles([$admin,$empleado]);
        Permission::create(['name'=>'productos.edit'])->syncRoles([$admin,$empleado]);
        Permission::create(['name'=>'productos.update'])->syncRoles([$admin,$empleado]);
        Permission::create(['name'=>'productos.store'])->syncRoles([$admin,$empleado]);
        Permission::create(['name'=>'productos.destroy'])->syncRoles([$admin]);
        Permission::create(['name'=>'productos.allProduct'])->syncRoles([$admin,$empleado]);
        Permission::create(['name'=>'productos.actualizar'])->syncRoles([$admin,$empleado]);


        Permission::create(['name'=>'categoriaClientes.index'])->syncRoles([$admin,$empleado]);
        Permission::create(['name'=>'categoriaClientes.create'])->syncRoles([$admin]);
        Permission::create(['name'=>'categoriaClientes.edit'])->syncRoles([$admin]);
        Permission::create(['name'=>'categoriaClientes.update'])->syncRoles([$admin]);
        Permission::create(['name'=>'categoriaClientes.store'])->syncRoles([$admin]);
        Permission::create(['name'=>'categoriaClientes.destroy'])->syncRoles([$admin]);
        Permission::create(['name'=>'categoriaClientes.allCategori'])->syncRoles([$admin,$empleado]);

       
    
        Permission::create(['name'=>'banners.index'])->syncRoles([$admin,$empleado]);
        Permission::create(['name'=>'banners.create'])->syncRoles([$admin,$empleado]);
        Permission::create(['name'=>'banners.edit'])->syncRoles([$admin,$empleado]);
        Permission::create(['name'=>'banners.update'])->syncRoles([$admin,$empleado]);
        Permission::create(['name'=>'banners.store'])->syncRoles([$admin,$empleado]);
        Permission::create(['name'=>'banners.destroy'])->syncRoles([$admin,$empleado]);
        Permission::create(['name'=>'banners.allBanners'])->syncRoles([$admin,$empleado]);

        Permission::create(['name'=>'ciudades.index'])->syncRoles([$admin,$empleado]);
        Permission::create(['name'=>'ciudades.create'])->syncRoles([$admin,$empleado]);
        Permission::create(['name'=>'ciudades.edit'])->syncRoles([$admin,$empleado]);
        Permission::create(['name'=>'ciudades.update'])->syncRoles([$admin,$empleado]);
        Permission::create(['name'=>'ciudades.store'])->syncRoles([$admin,$empleado]);
        Permission::create(['name'=>'ciudades.destroy'])->syncRoles([$admin]);

        Permission::create(['name'=>'paises.index'])->syncRoles([$admin,$empleado]);
        Permission::create(['name'=>'paises.create'])->syncRoles([$admin,$empleado]);
        Permission::create(['name'=>'paises.edit'])->syncRoles([$admin,$empleado]);
        Permission::create(['name'=>'paises.update'])->syncRoles([$admin,$empleado]);
        Permission::create(['name'=>'paises.store'])->syncRoles([$admin,$empleado]);
        Permission::create(['name'=>'paises.destroy'])->syncRoles([$admin]);

        // Permission::create(['name'=>'pqrs.update'])->syncRoles([$admin,$empleado]);
        // Permission::create(['name'=>'perfil.create'])->syncRoles([$admin]);
        // Permission::create(['name'=>'perfil.edit'])->syncRoles([$admin]);
        // Permission::create(['name'=>'perfil.update'])->syncRoles([$admin]);
        // Permission::create(['name'=>'perfil.store'])->syncRoles([$admin]);
        // Permission::create(['name'=>'perfil.destroy'])->syncRoles([$admin]);

    }
}

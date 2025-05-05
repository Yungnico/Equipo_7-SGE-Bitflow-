<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Asegúrate de importar el modelo User
use Spatie\Permission\Models\Role; // Asegúrate de importar el modelo Role

class CrudUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Puedes pasar datos a la vista si es necesario
        $users = User::all(); // Asegúrate de importar el modelo User
        return view('admin.viewusers.index', compact('users')); // Pasa la variable $users a la vista
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all(); // Asegúrate de importar el modelo Role
        return view('admin.viewusers.edit', compact('user', 'roles')); // Pasa la variable $user a la vista
    }

    /**
     * Update the specified resource in storage.
     */
        public function update(Request $request, $id)
    {
        $request->validate([
            'roles' => 'required|array', 
        ]);
        $user = User::findOrFail($id); // Busca el usuario por ID

        $user->roles()->sync($request->roles); // sincroniza los roles del usuario con los roles seleccionados

        return redirect()->route('viewusers.edit', $user)->with('info', 'Rol asignado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

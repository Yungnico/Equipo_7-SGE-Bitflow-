<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use App\Notifications\ResetPasswordNotification;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|confirmed',
            'roles' => 'required|array'
        ]);

        $randomPassword = \Illuminate\Support\Str::random(10);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($randomPassword),
        ]);

        // Asignar roles si vienen seleccionados
        if ($request->filled('roles')) {
            $user->assignRole($request->roles); // Acepta array o string
        }

        // Enviar notificación con el token
        $token = \Illuminate\Support\Facades\Password::broker()->createToken($user);
        $user->notify(new \App\Notifications\ResetPasswordNotification($token));

        return redirect()->route('users.create')->with('success', 'Usuario creado correctamente');
    }
}

<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use App\Notifications\ResetPasswordNotification;

class UserController extends Controller
{

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|confirmed',
        ]);

        $randomPassword = Str::random(10);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($randomPassword),
        ]);

        $token = Password::broker()->createToken($user);
        $user->notify(new ResetPasswordNotification($token));

        return redirect()->route('users.create')->with('success', 'Usuario creado correctamente');
        
    }
}

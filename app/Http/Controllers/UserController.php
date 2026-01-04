<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|email',
            'password' => 'required'
        ]);
        
        $user = User::where('email', $request->login)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->error('Login yoki parol xato');
        }

        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->success([
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function logout(Request $request)
    {
        $user = auth()->user();
        $user->tokens()->delete();
        return $this->success([], "successfully logouted user", 203);
    }
}
<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController
{
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'fcm' => ['required', 'string'],
        ]);

        $user = User::with('worker')->where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email yoki parol noto\'g\'ri.'],
            ]);
        }

        if (!$user->worker) {
            throw ValidationException::withMessages([
                'email' => ['Bu foydalanuvchi xodim sifatida ro\'yxatdan o\'tmagan.'],
            ]);
        }

        $user->tokens()->delete();
        $user->update(['fcm' => $request->fcm]);

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'worker' => [
                'id' => $user->worker->id,
                'title' => $user->worker->title,
                'phone_number' => $user->worker->phone_number,
            ],
        ]);
    }
}
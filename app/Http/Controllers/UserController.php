<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|email',
            'password' => 'required'
        ]);
        
        $user = User::with('neighborood')
            ->where('email', $request->login)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->error('Login yoki parol xato');
        }

        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->success([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'neighborood_id' => $user->neighborood?->id,
                'neighborood' => $user->neighborood,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $user = auth()->user();
        $user->tokens()->delete();
        return $this->success([], "successfully logouted user", 203);
    }

    public function profile()
    {
        $user = auth()->user();
        $neighborood = $user->neighborood;
        if (!$neighborood) {
            return $this->success([
                'new' => 0,
                'cancelled' => 0,
                'apply' => 0,
            ]);
        }
        $stats = $neighborood->tasks()
            ->selectRaw("
                SUM(status = 'new')       as new,
                SUM(status = 'cancelled') as cancelled,
                SUM(status = 'apply')     as apply
            ")
            ->first();
        return $this->response([
            'new'       => (int) $stats->new,
            'cancelled' => (int) $stats->cancelled,
            'apply'     => (int) $stats->apply,
        ]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $data = [];
        if ($request->filled('email')) {
            $request->validate([
                'email' => 'email|unique:users,email,' . $user->id,
            ]);
            $data['email'] = $request->email;
        }
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'min:6',
            ]);
            $data['password'] = Hash::make($request->password);
        }
        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'image|max:2048',
            ]);
            if ($user->image) {
                $oldPath = str_replace('/storage/', '', $user->image);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('image')->store('users', 'public');

            $data['image'] = asset('storage/' . $path);
        }
        if (empty($data)) {
            return $this->error('O‘zgartirish uchun maʼlumot yuborilmadi');
        }
        $user->update($data);
        return $this->success($user->fresh(), "Profile muvaffaqiyatli yangilandi");
    }
}
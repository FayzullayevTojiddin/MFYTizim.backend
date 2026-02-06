<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class GetProfileController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->load('worker');

        return response()->json([
            'success' => true,
            'message' => null,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'image' => $user->image,
                'role' => $user->role?->value ?? $user->role,
                'status' => $user->status,
                'last_seen_at' => $user->last_seen_at?->toISOString(),
                'worker' => $user->worker ? [
                    'id' => $user->worker->id,
                    'title' => $user->worker->title,
                    'phone_number' => $user->worker->phone_number,
                ] : null,
            ],
        ]);
    }
}
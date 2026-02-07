<?php

namespace App\Http\Controllers\Worker;

use App\Models\WorkerLocation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller; 

class SetWorkerLocationController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $worker = $request->user()->worker;

        if (!$worker) {
            return response()->json([
                'success' => false,
                'message' => 'Worker topilmadi',
            ], 404);
        }

        $validated = $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'address' => 'nullable|string|max:500',
            'accuracy' => 'nullable|numeric|min:0',
            'battery_level' => 'nullable|numeric|between:0,100',
        ]);

        $location = WorkerLocation::create([
            'worker_id' => $worker->id,
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'address' => $validated['address'] ?? null,
            'accuracy' => $validated['accuracy'] ?? null,
            'battery_level' => $validated['battery_level'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Joylashuv saqlandi',
            'data' => [
                'id' => $location->id,
                'latitude' => $location->latitude,
                'longitude' => $location->longitude,
                'created_at' => $location->created_at->toISOString(),
            ],
        ]);
    }
}
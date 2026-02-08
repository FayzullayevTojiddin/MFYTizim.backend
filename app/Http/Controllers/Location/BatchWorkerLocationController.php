<?php

namespace App\Http\Controllers\Location;

use App\Models\WorkerLocation;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class BatchWorkerLocationController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $worker = $request->user()->worker;
        if (!$worker) {
            return response()->json(['success' => false], 404);
        }

        $validated = $request->validate([
            'locations' => 'required|array',
            'locations.*.latitude' => 'required|numeric|between:-90,90',
            'locations.*.longitude' => 'required|numeric|between:-180,180',
            'locations.*.accuracy' => 'nullable|numeric|min:0',
            'locations.*.battery_level' => 'nullable|numeric|between:0,100',
            'locations.*.is_real_time' => 'nullable|boolean',
            'locations.*.recorded_at' => 'nullable|date',
        ]);

        foreach ($validated['locations'] as $loc) {
            WorkerLocation::create([
                'worker_id' => $worker->id,
                'latitude' => $loc['latitude'],
                'longitude' => $loc['longitude'],
                'accuracy' => $loc['accuracy'] ?? null,
                'battery_level' => $loc['battery_level'] ?? null,
                'is_real_time' => $loc['is_real_time'] ?? false,
                'created_at' => $loc['recorded_at'] ?? now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => count($validated['locations']) . ' ta joylashuv saqlandi',
        ]);
    }
}
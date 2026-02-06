<?php

namespace App\Http\Controllers\Meet;

use App\Models\Meet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AceptMeetController extends Controller
{
    public function __invoke(Request $request, Meet $meet): JsonResponse
    {
        $worker = $request->user()->worker;

        if (!$worker) {
            return response()->json([
                'success' => false,
                'message' => 'Worker topilmadi',
            ], 404);
        }

        // Validate request
        $request->validate([
            'status' => 'required|in:accepted,rejected',
        ]);

        // Check if worker is invited to this meet
        $meetWorker = $meet->meetWorkers()
            ->where('worker_id', $worker->id)
            ->first();

        if (!$meetWorker) {
            return response()->json([
                'success' => false,
                'message' => 'Siz ushbu uchrashuvga taklif qilinmagansiz',
            ], 403);
        }

        // Check if meet is not past
        if ($meet->meet_at->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'Uchrashuv vaqti o\'tib ketgan',
            ], 400);
        }

        // Check if meet is not cancelled
        if ($meet->status === 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'Uchrashuv bekor qilingan',
            ], 400);
        }

        // Update pivot
        $meetWorker->update([
            'status' => $request->status,
            'responded_at' => now(),
            'seen_at' => now()
        ]);

        $statusLabel = $request->status === 'accepted' 
            ? 'Qabul qildingiz' 
            : 'Rad etdingiz';

        return response()->json([
            'success' => true,
            'message' => $statusLabel,
            'data' => [
                'meet_id' => $meet->id,
                'status' => $request->status,
                'responded_at' => now()->toISOString(),
            ],
        ]);
    }
}
<?php

namespace App\Http\Controllers\Meet;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class GetListMeetController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $worker = $request->user()->worker;

        if (!$worker) {
            return response()->json([
                'success' => false,
                'message' => 'Worker topilmadi',
                'data' => [],
            ], 404);
        }

        $query = $worker->meets()->with('creator');

        // Vaqt bo'yicha filter: upcoming (default), past, all
        $filter = $request->query('filter', 'upcoming');

        if ($filter === 'upcoming') {
            $query->where('meet_at', '>=', now());
        } elseif ($filter === 'past') {
            $query->where('meet_at', '<', now());
        }

        // Meet statusi bo'yicha: pending, active, completed, cancelled
        if ($request->has('status')) {
            $query->where('status', $request->query('status'));
        }

        // Mening javobim bo'yicha: pending, accepted, rejected
        if ($request->has('my_status')) {
            $query->wherePivot('status', $request->query('my_status'));
        }

        $meets = $query
            ->orderBy('meet_at', $filter === 'past' ? 'desc' : 'asc')
            ->get()
            ->map(function ($meet) {
                return [
                    'id' => $meet->id,
                    'title' => $meet->title,
                    'description' => $meet->description,
                    'address' => $meet->address,
                    'location' => $meet->location,
                    'meet_at' => $meet->meet_at->toISOString(),
                    'meet_date' => $meet->meet_at->format('d.m.Y'),
                    'meet_time' => $meet->meet_at->format('H:i'),
                    'is_today' => $meet->meet_at->isToday(),
                    'is_past' => $meet->meet_at->isPast(),
                    'status' => $meet->status,
                    'status_label' => match($meet->status) {
                        'pending' => 'Kutilmoqda',
                        'active' => 'Faol',
                        'completed' => 'Yakunlangan',
                        'cancelled' => 'Bekor qilingan',
                        default => $meet->status,
                    },
                    'creator' => $meet->creator ? [
                        'id' => $meet->creator->id,
                        'name' => $meet->creator->name,
                    ] : null,
                    'my_status' => $meet->pivot->status,
                    'my_status_label' => match($meet->pivot->status) {
                        'pending' => 'Javob berilmagan',
                        'accepted' => 'Qabul qilingan',
                        'rejected' => 'Rad etilgan',
                        default => $meet->pivot->status,
                    },
                    'seen_at' => $meet->pivot->seen_at,
                    'responded_at' => $meet->pivot->responded_at,
                    'workers_count' => $meet->workers()->count(),
                    'accepted_count' => $meet->acceptedWorkers()->count(),
                ];
            });

        return response()->json([
            'success' => true,
            'message' => null,
            'data' => $meets,
        ]);
    }
}
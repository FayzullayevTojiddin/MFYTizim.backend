<?php

namespace App\Http\Controllers\Work;

use App\Models\Worker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;

class GetRankWorkerController extends Controller
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

        $rankings = $this->getRankings();

        $myRank = null;
        $myStats = null;

        foreach ($rankings as $index => $item) {
            if ($item['id'] === $worker->id) {
                $myRank = $index + 1;
                $myStats = $item;
                break;
            }
        }

        $topWorkers = array_slice($rankings, 0, 10);

        $topWorkers = array_map(function ($item, $index) {
            return [
                'rank' => $index + 1,
                'id' => $item['id'],
                'name' => $item['user']['name'] ?? 'Noma\'lum',
                'image' => $item['user']['image'] ?? null,
                'title' => $item['title'],
                'total_tasks' => $item['total_tasks'],
                'completed_tasks' => $item['completed_tasks'],
                'pending_tasks' => $item['pending_tasks'],
                'overdue_tasks' => $item['overdue_tasks'],
                'completion_rate' => $item['total_tasks'] > 0
                    ? round(($item['completed_tasks'] / $item['total_tasks']) * 100)
                    : 0,
            ];
        }, $topWorkers, array_keys($topWorkers));

        return response()->json([
            'success' => true,
            'message' => null,
            'data' => [
                'my_rank' => $myRank,
                'total_workers' => count($rankings),
                'my_stats' => $myStats ? [
                    'id' => $myStats['id'],
                    'name' => $myStats['user']['name'] ?? 'Noma\'lum',
                    'image' => $myStats['user']['image'] ?? null,
                    'title' => $myStats['title'],
                    'total_tasks' => $myStats['total_tasks'],
                    'completed_tasks' => $myStats['completed_tasks'],
                    'pending_tasks' => $myStats['pending_tasks'],
                    'overdue_tasks' => $myStats['overdue_tasks'],
                    'completion_rate' => $myStats['total_tasks'] > 0
                        ? round(($myStats['completed_tasks'] / $myStats['total_tasks']) * 100)
                        : 0,
                ] : null,
                'top_workers' => $topWorkers,
            ],
        ]);
    }

    protected function getRankings(): array
    {
        return Worker::query()
            ->with('user:id,name,image')
            ->withCount([
                'tasks as total_tasks',
                'tasks as completed_tasks' => fn (Builder $q) => $q->whereNotNull('completed_at'),
                'tasks as pending_tasks' => fn (Builder $q) => $q->whereNull('completed_at'),
                'tasks as overdue_tasks' => fn (Builder $q) => $q
                    ->whereNull('completed_at')
                    ->where('deadline_at', '<', now()),
            ])
            ->orderByDesc('completed_tasks')
            ->orderBy('overdue_tasks')
            ->get()
            ->toArray();
    }
}
<?php

namespace App\Http\Controllers\Task;

use App\Models\TaskCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;

class ListTaskCategoryController extends Controller
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

        // Kategoriyalar (1 soatga cache)
        $categories = Cache::remember('task_categories', 3600, function () {
            return TaskCategory::query()
                ->where('status', true)
                ->orderBy('title')
                ->get(['id', 'title', 'description']);
        });

        // Har bir kategoriya uchun worker vazifalarini olish
        $data = $categories->map(function ($category) use ($worker) {
            $tasks = $worker->tasks()
                ->where('task_category_id', $category->id)
                ->get();

            $totalCount = $tasks->sum('count');
            $approvedCount = $tasks->sum('approved_count');
            $pendingCount = $totalCount - $approvedCount;
            $overdueCount = $tasks->filter(fn($t) => $t->is_overdue && $t->approved_count < $t->count)->count();

            return [
                'id' => $category->id,
                'title' => $category->title,
                'description' => $category->description,
                'stats' => [
                    'total_tasks' => $tasks->count(),
                    'total_count' => $totalCount,
                    'approved_count' => $approvedCount,
                    'pending_count' => $pendingCount,
                    'overdue_count' => $overdueCount,
                    'completion_rate' => $totalCount > 0 
                        ? round(($approvedCount / $totalCount) * 100) 
                        : 0,
                ],
                'tasks' => $tasks->map(function ($task) {
                    return [
                        'id' => $task->id,
                        'count' => $task->count,
                        'approved_count' => $task->approved_count,
                        'deadline_at' => $task->deadline_at->toISOString(),
                        'deadline_date' => $task->deadline_at->format('d.m.Y'),
                        'is_overdue' => $task->is_overdue,
                        'is_priority' => $task->is_priority,
                        'completed_at' => $task->completed_at?->toISOString(),
                    ];
                })->values(),
            ];
        })->filter(fn($cat) => $cat['stats']['total_tasks'] > 0)->values();

        return response()->json([
            'success' => true,
            'message' => null,
            'data' => $data,
        ]);
    }
}
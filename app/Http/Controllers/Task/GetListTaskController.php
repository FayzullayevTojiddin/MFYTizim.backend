<?php

namespace App\Http\Controllers\Task;

use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetListTaskController
{
    public function __invoke(Request $request): JsonResponse
    {
        $worker = $request->user()->worker;

        if (!$worker) {
            return response()->json([
                'message' => 'Worker topilmadi',
            ], 404);
        }

        $tasks = Task::where('worker_id', $worker->id)
            ->whereNull('completed_at')
            ->with(['category', 'myTasks'])
            ->orderByRaw('CASE WHEN task_category_id = 1 THEN 0 ELSE 1 END')
            ->orderBy('deadline_at')
            ->get();

        $tasks = $tasks->map(function ($task) {
            $approvedCount = $task->myTasks->where('status', 'approved')->count();

            return [
                'id' => $task->id,
                'task_category_id' => $task->task_category_id,
                'category' => [
                    'id' => $task->category->id,
                    'name' => $task->category->name,
                ],
                'count' => $task->count,
                'approved_count' => $approvedCount,
                'deadline_at' => $task->deadline_at->toISOString(),
                'is_overdue' => $task->isOverdue(),
                'is_priority' => $task->task_category_id === 1,
            ];
        });

        return response()->json([
            'data' => $tasks,
        ]);
    }
}
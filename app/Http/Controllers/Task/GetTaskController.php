<?php

namespace App\Http\Controllers\Task;

use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetTaskController
{
    public function __invoke(Request $request, Task $task): JsonResponse
    {
        $worker = $request->user()->worker;

        if (!$worker || $task->worker_id !== $worker->id) {
            return response()->json([
                'message' => 'Vazifa topilmadi',
            ], 404);
        }

        $task->load(['category', 'myTasks']);

        $approvedCount = $task->myTasks->where('status', true)->count();

        return response()->json([
            'data' => [
                'id' => $task->id,
                'task_category_id' => $task->task_category_id,
                'category' => [
                    'id' => $task->category->id,
                    'name' => $task->category->name,
                ],
                'count' => $task->count,
                'approved_count' => $approvedCount,
                'deadline_at' => $task->deadline_at->toISOString(),
                'completed_at' => $task->completed_at?->toISOString(),
                'is_overdue' => $task->isOverdue(),
                'is_priority' => $task->task_category_id === 1,
                'my_tasks' => $task->myTasks->map(function ($myTask) {
                    return [
                        'id' => $myTask->id,
                        'description' => $myTask->description,
                        'files' => $myTask->files,
                        'status' => $myTask->status ? 'approved' : 'pending',
                        'location' => $myTask->location,
                        'created_at' => $myTask->created_at->toISOString(),
                    ];
                }),
            ],
        ]);
    }
}
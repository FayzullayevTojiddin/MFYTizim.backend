<?php

namespace App\Http\Controllers\Task;

use App\Models\MyTask;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostTaskController
{
    public function __invoke(Request $request, Task $task): JsonResponse
    {
        $worker = $request->user()->worker;

        if (!$worker || $task->worker_id !== $worker->id) {
            return response()->json([
                'message' => 'Vazifa topilmadi',
            ], 404);
        }

        $request->validate([
            'description' => ['nullable', 'string', 'max:2000'],
            'files' => ['nullable', 'array'],
            'files.*' => [
                'file',
                'max:102400',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp,image/jpg,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/octet-stream',
            ],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
        ]);

        // Kamida description yoki files bo'lishi kerak
        if (!$request->filled('description') && !$request->hasFile('files')) {
            return response()->json([
                'message' => 'Kamida tavsif yoki fayl yuborish kerak',
                'errors' => [
                    'description' => ['Kamida tavsif yoki fayl yuborish kerak'],
                ],
            ], 422);
        }

        $uploadedFiles = [];

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('tasks/' . $task->id, 'public');

                $uploadedFiles[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'url' => '/storage/' . $path,
                    'size' => $file->getSize(),
                    'mime' => $file->getMimeType(),
                ];
            }
        }

        $location = null;
        if ($request->filled('latitude') && $request->filled('longitude')) {
            $location = [
                'lat' => $request->latitude,
                'lng' => $request->longitude,
            ];
        }

        $myTask = MyTask::create([
            'task_id' => $task->id,
            'description' => $request->input('description'),
            'files' => !empty($uploadedFiles) ? $uploadedFiles : null,
            'location' => $location,
            'status' => false,
        ]);

        return response()->json([
            'message' => 'Muvaffaqiyatli yuklandi',
            'data' => [
                'id' => $myTask->id,
                'description' => $myTask->description,
                'files' => $myTask->files,
                'status' => 'pending',
                'location' => $myTask->location,
                'created_at' => $myTask->created_at->toISOString(),
            ],
        ], 201);
    }
}
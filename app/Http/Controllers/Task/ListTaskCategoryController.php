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
        $categories = Cache::remember('task_categories', 3600, function () {
            return TaskCategory::query()
                ->where('status', true)
                ->orderBy('title')
                ->get()
                ->map(fn ($category) => [
                    'id' => $category->id,
                    'title' => $category->title,
                    'description' => $category->description,
                ]);
        });

        return response()->json([
            'success' => true,
            'message' => null,
            'data' => $categories,
        ]);
    }
}
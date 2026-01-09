<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Task;
use Illuminate\Support\Str;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with('category')
            ->where('neighborood_id', auth()->user()->neighborood?->id)
            ->whereDate('created_at', Carbon::today())
            ->get();

        return $this->response($tasks);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'description'       => 'required|string',
            'task_category_id'  => 'required|exists:task_categories,id',
            'latitude'          => 'required|numeric',
            'longitude'         => 'required|numeric',
            'file' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,pdf,ppt,pptx,txt',
        ]);

        $data['neighborood_id'] = auth()->user()->neighborood?->id;

        if ($request->hasFile('file')) {
            $file = $request->file('file');

            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

            $path = $file->storeAs(
                'tasks/files',
                $filename,
                'public'
            );

            $data['file'] = $path;
        }

        $task = Task::create($data);

        return $this->success($task, null, 201);
    }
}
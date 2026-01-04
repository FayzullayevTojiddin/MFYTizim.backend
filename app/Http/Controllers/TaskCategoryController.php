<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskCategory;

class TaskCategoryController extends Controller
{
    public function index()
    {
        $categories = TaskCategory::all();
        return $this->response($categories);
    }
}

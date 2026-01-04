<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meet;
use Carbon\Carbon;

class MeetController extends Controller
{
    public function index()
    {
        $meets = Meet::whereDate('date_at', '>=', Carbon::today())
            ->orderBy('date_at', 'asc')
            ->get();

        return response()->json($meets);
    }
}
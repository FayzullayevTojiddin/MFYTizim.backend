<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function response($data)
    {
        return response()->json([
            'data' => $data
        ]);
    }

    public function error($message, $status = 401)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => []
        ], $status);
    }

    public function success($data, $message = null, $status = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $status);
    }
}

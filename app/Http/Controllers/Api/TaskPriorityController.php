<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TaskPriority;
use Illuminate\Http\JsonResponse;

class TaskPriorityController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => TaskPriority::orderBy('id')->get(),
        ]);
    }
}

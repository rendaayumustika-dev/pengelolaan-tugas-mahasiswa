<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Status;
use Illuminate\Http\JsonResponse;

class StatusController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => Status::orderBy('id')->get(),
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        return response()->json([
            'message' => 'Register berhasil'
        ]);
    }

    public function login(Request $request)
    {
        return response()->json([
            'message' => 'Login berhasil'
        ]);
    }

    public function profile()
    {
        return response()->json([
            'message' => 'Profile user'
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'message' => 'Refresh token'
        ]);
    }

    public function logout()
    {
        return response()->json([
            'message' => 'Logout berhasil'
        ]);
    }
}
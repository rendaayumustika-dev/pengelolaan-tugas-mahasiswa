<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
{
    $validator = Validator::make(
        $request->all(),
        [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]
    );

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => $validator->errors()
        ], 400);
    }

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password)
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Register berhasil',
        'data' => $user
    ], 201);
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
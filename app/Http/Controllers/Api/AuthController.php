<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\ActivityLog;

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
    $validator = Validator::make(
        $request->all(),
        [
            'email' => 'required|email',
            'password' => 'required'
        ]
    );

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => $validator->errors()
        ], 400);
    }

    $credentials = $request->only('email', 'password');

    if (!$token = Auth::guard('api')->attempt($credentials)) {
        return response()->json([
            'status' => false,
            'message' => 'Email atau password salah'
        ], 401);
    }

    return response()->json([
        'status' => true,
        'message' => 'Login berhasil',
        'access_token' => $token,
        'token_type' => 'bearer'
    ]);
}

    public function profile()
{
    return response()->json([
        'status' => true,
        'message' => 'Data user',
        'data' => auth()->user()
    ]);
}

    public function refresh()
{
    return response()->json([
        'status' => true,
        'message' => 'Token berhasil diperbarui',
        'access_token' => auth()->refresh(),
        'token_type' => 'bearer'
    ]);
}

    public function logout()
{
    auth()->logout();

    return response()->json([
        'status' => true,
        'message' => 'Logout berhasil'
    ]);
}
}
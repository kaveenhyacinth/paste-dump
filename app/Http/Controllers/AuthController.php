<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private string $api_token_key = 'myapitoken';

    public function register(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
        ]);

        $username = explode('@', $fields['email'])[0] . rand(100, 999);

        $user = User::create([
            'username' => $username,
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
        ]);

        $token = $user->createToken($this->api_token_key)->plainTextToken;

        return response([
            'message' => 'User created successfully',
            'data' => $user,
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $fields['email'])->first();

        if (!$user) {
            return response([
                'message' => 'User not found! Invalid email',
            ], 404);
        }

        if (!Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Invalid password',
            ], 401);
        }

        $token = $user->createToken($this->api_token_key)->plainTextToken;

        return response([
            'message' => 'Logged in successfully',
            'data' => $user,
            'token' => $token,
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response([
            'message' => 'Logged out',
        ], 200);
    }
}

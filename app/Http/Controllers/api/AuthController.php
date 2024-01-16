<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'confirm_password' => 'required|string|min:6|same:password'
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));
    
        $token = $user->createToken('MyApp')->accessToken;
    
        return response()->json(['user' => $user, 'token' => $token], 201);
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 422);
        }
    
        if (!Auth::attempt($validator->validated())) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    
        $user = $request->user();
        $token = $user->createToken('MyApp')->accessToken;
    
        return response()->json(['user' => $user, 'token' => $token]);
    }
    

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response(['message' => 'Successfully logged out']);
    }

    public function refreshToken(Request $request)
    {
        $user = $request->user();

        $user->tokens()->delete();

        return response(['token' => $user->createToken('MyApp')->plainTextToken]);
    }
    

    public function user(Request $request)
    {
        $user = $request->user();
    
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], Response::HTTP_UNAUTHORIZED);
        }
    
        return response()->json(['user' => $user]);
    }
}

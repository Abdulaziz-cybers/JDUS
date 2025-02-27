<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\AuthRegisterRequest;
use App\Http\Requests\AuthLoginRequest;

class AuthController extends Controller
{
    public function register(AuthRegisterRequest $request){
        $validator = $request->validated();
        User::query()->create($validator);
        return response()->json(['message' => 'User successfully registered']);
    }
    public function login(AuthLoginRequest $request){
        $validator = $request->validated();

        $email = $validator['email'];
        $password = $validator['password'];
        $user = User::query()->where('email', $email)->first();

        if(!$user || !Hash::check($password, $user->password)){
            return response()->json(['message' => 'Invalid Credentials'], 401);
        }
        $token = $user->createToken('token')->plainTextToken;
        return response()
            ->json([
                'message' => 'Login Successful',
                'token' => $token,
                'user' => $user,
            ]);
    }
    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logout Successful']);
    }
}

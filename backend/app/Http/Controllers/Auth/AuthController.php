<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

//use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends  Controller
{
    public function login(Request $request)
    {
        // Authentication logic here
        // ...
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $token = JWTAuth::fromUser(Auth::user());
            if (Auth::user()->hasRole('user-customer')){
                return response()->json(['token' => $token,'role'=>'user-customer']);
            }else{
                return response()->json(['token' => $token,'role'=>'super-admin']);
            }
        }
        return response()->json(['error' => 'Invalid credentials'], 401);

    }

    public function logout()
    {
        JWTAuth::invalidate();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        $newToken = JWTAuth::refresh();

        return response()->json(['token' => $newToken]);
    }

    public function user()
    {
        $user = JWTAuth::user();

        return response()->json(['user' => $user]);
    }
}

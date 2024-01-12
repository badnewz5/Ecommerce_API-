<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;


class LoginController extends Controller
{
    public function login(Request $request)
    {
        try{
            $credentials = $request->only('email', 'password');

            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

//        $user = Auth::user();
//        $token = $user->createToken('auth_token')->accessToken;
        return response()->json(compact('token'));
//        return $this->respondWithToken($token);
        // return response()->json([
        //     'access_token' => $token,
        //     'token_type' => 'bearer',
        //     'expires_in' => now()->addDays(1)->timestamp, // Adjust the expiration as needed
        // ]);
    }
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => now()->addDays(1)->timestamp, // Adjust the expiration as needed
        ]);
    }
}


<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Product\ProductRequest;
use App\Models\Product;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
class ProductController extends Controller
{
    //
    public function index(Request $request)
    {
        try {
            // Attempt to verify the token
            $user = JWTAuth::parseToken()->authenticate();
            // User is authenticated
            $userId = $user->id;
            $product = Product::latest()->get();
            return response()->json(['product' => $product]);
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            // Token has expired
            return response()->json(['error' => 'Token expired'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            // Token is invalid
            return response()->json(['error' => 'Token invalid'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            // Token is absent
            return response()->json(['error' => 'Token absent'], 401);
        }




    }
    public function store(ProductRequest $request)
    {

        if ($request->user()->hasRole('user-customer')){
            return response()->json(['message' => "Only admin can create product"], 401);

        }else {
            $validatedData = $request->validated();

            // Create a new product
            $product = Product::create($validatedData);

            return response()->json(['product' => $product], 201);
        }
    }
}

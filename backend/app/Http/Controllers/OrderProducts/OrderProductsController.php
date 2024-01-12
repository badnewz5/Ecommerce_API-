<?php

namespace App\Http\Controllers\OrderProducts;

use App\Http\Controllers\Controller;
use App\Models\OrderProducts;
use Illuminate\Http\Request;
use App\Http\Requests\OrderProducts\OrderProductsRequest;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class OrderProductsController extends Controller
{
    public function userOrdersHistory()
    {
        $user = Auth::user();

        $Orderuserhistory = OrderProducts::with('products')->where('user_id', $user->id)->get();
        return response()->json(['Orderuserhistory' => $Orderuserhistory, 'message' => 'Your Order History'], 200);
    }

    public function store(OrderProductsRequest $request)
    {
        if($user = JWTAuth::parseToken()->authenticate()){
            $validator = $request->validated();
            $orderproducts = OrderProducts::create([
                'order_id' => $request->order_id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
            // Attach products to the order
            foreach ($request->input('products') as $product) {
                $orderproducts->products()->attach($product['id'], ['quantity' => $product['quantity']]);
            }

            return response()->json(['orderproducts' => $orderproducts, 'message'=>'Order products create successfully'], 2001);

        }
    }
}

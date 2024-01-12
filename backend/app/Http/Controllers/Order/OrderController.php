<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\OderRequest;
use App\Models\Order;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Stripe;

class OrderController extends Controller
{
    public  function index()
    {
        $order = Order::all();
        return response()->json(['order' => $order, 'message'=>'view all order'], 200);
    }

    public function show($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found please try agin ?'], 404);
        }

        return response()->json(['order' => $order], 200);
    }


    public  function store(OderRequest $request)
    {
        if($user = JWTAuth::parseToken()->authenticate()){
            $validator = $request->validated();

            $orders = Order::create([
                'user_id' => $user->id,
                'payment_status' => $request->payment_status,
                'total_amount' => $request->total_amount,
            ]);

            return response()->json(['orders' => $orders, 'message'=>'Order create successfully'], 2001);

        }
    }
    public function update(Request $request, $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'total_amount' => 'required|numeric',
            'payment_status' => 'required|in:pending,completed,failed',
        ]);

        $order->update($request->all());

        return response()->json(['order' => $order], 200);
    }

    public function destroy($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order->delete();

        return response()->json(['message' => 'Order deleted successfully'], 200);
    }

    public function userOrders(Request $request)
    {
         $user = $request->user();
        $orders = Order::where('user_id', $user->id)->get();

        return response()->json(['orders' => $orders], 200);
    }

    public  function  processStripePayment(Request $request, $id)
    {
        // Assuming you're using authentication
        $user = Auth::user();


        // Find the order

        $order = Order::findOrFall($id);


        // Validate the request data
            $validator = Validator::make($request->all(), [
            'stripe_token' => 'required|string',
           ]);
            if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
            }


        // Process payment using Stripe
        try {
            // Set your Stripe API key.
            \Stripe\Stripe::setApiKey(config('stripe.sk'));

            // Get the payment amount and email address from the form.
            $amount = $request->get('amount') * 100;
            $email = $request->get('email');
            $product = $request->get('product');


            // Create a new Stripe customer.
            $customer = \Stripe\Customer::create([
                'email' => $email,
                'source' => $request->input('stripeToken'),
            ]);

            $customer = \Stripe\Customer::create([
                'email' => $email,
                'source' => $request->input('stripe_token'),
            ]);

            // Create a new Stripe charge.
            $charge = \Stripe\Charge::create([
                'customer' => $customer->id,
                'amount' => $amount,
                'currency' => 'tzs',
                'product' => $product,
            ]);


        } catch (\Exception $e) {
            return response()->json(['error' => 'Payment failed: ' . $e->getMessage()], 422);
        }
        return response()->json(['charge'=>$charge,'message' => 'Payment processed successfully']);

    }
}

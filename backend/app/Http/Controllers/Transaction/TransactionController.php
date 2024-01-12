<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\TransactionRequest;
use App\Models\Order;
use App\Models\transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    //
    public function createTransaction(TransactionRequest $request, $orderid)
    {
        // Validation check
        $validatedData = $request->validated();

        // Find the order
        $order = Order::findOrFail($orderid);

        // Create a new transaction

        $transaction = transaction::create($validatedData);
        $order->transactions()->save($transaction);
        return response()->json(['transaction'=> $transaction, 'message'=> 'Successfull create transaction'],201);
    }

    public function getTransactions($orderId)
    {
        // Find the order
        $order = Order::findOrFail($orderId);

        // Retrieve the transactions for the order
        $transactions = $order->transactions;

        return response()->json(['transactions' =>$transactions, 'message'=> 'view transaction'], 200);
    }


}

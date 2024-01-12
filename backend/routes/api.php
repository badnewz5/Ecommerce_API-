<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Refarral\ReferralController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Order\OrderController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

 Route::middleware('auth:api')->get('/user', function (Request $request) {
     return $request->user();
 });

// Route::middleware(['auth:api'])->group(function () {
//     Route::post('/register', [UserController::class, 'register']);
//     Route::post('/logout', [\App\Http\Controllers\Auth\LogoutController::class, 'logout']);
//
// });


Route::get('/home', function () {
    return response()->json(['message' => 'Thank you for visiting']);
})->name('home');
//Route::middleware('auth:api')->post('/logout', [\App\Http\Controllers\Auth\LogoutController::class, 'logout']);
Route::group(['middleware' => 'custom.jwt'], function() {
    Route::post('/logout', [\App\Http\Controllers\Auth\LogoutController::class, 'logout']);

    Route::name('admin.')->prefix('admin')->group(function () {
        Route::get('/product', [ProductController::class, 'index']);
        Route::post('/addproduct', [ProductController::class, 'store']);
        Route::get('/referrals', [ReferralController::class, 'index']);
        Route::post('/referrals', [ReferralController::class, 'store']);
        Route::put('/referrals/{id}', [ReferralController::class, 'index']);

//        Route::apiResource('referrals', ReferralController::class);

    });
});

Route::group(['middleware' => 'custom.jwt'], function() {
    Route::name('user.')->prefix('user')->group(callback: function () {
        Route::get('/product', [ProductController::class, 'index']);
        Route::post('/addproduct', [ProductController::class, 'store']);
        Route::get('/referrals', [ReferralController::class, 'index']);
        Route::post('/referrals', [ReferralController::class, 'store']);
        Route::put('/referrals/{id}', [ReferralController::class, 'update']);

        Route::post('/orders', [OrderController::class, 'store']);
        Route::get('/orders', [OrderController::class, 'index']);
        Route::put('/orders/{id}', [OrderController::class, 'update']);
        Route::delete('/orders/{id}', [OrderController::class, 'destroy']);
        Route::get('/orders/', [OrderController::class, 'userOrders']);


        // api route for create order and view user order history
        Route::post('/orders', [OrderController::class, 'store']);
        Route::get('orders', [OrderController::class, 'userOrdersHistory']);

        Route::post('orders/{id}/payment', [OrderController::class, 'processStripePayment']);


//        Route::apiResource('referrals', ReferralController::class);

    });
});
Route::post('/register', [UserController::class, 'register']);
//Route::post('/login', [LoginController::class, 'login'])->name("login");
Route::post('login', [\App\Http\Controllers\Auth\AuthController::class, 'login']);
//Route::get('/auth', [UserController::class, 'getAuthenticatedUser']);









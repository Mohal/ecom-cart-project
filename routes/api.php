<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CheckoutController;
use Illuminate\Http\Request;
// error_log("Debug @@ 7");

/* Route::post('/cart/items', function (Request $request) {
    return response()->json([
        'payload' => $request->all(),
        'user'    => auth()->user(),
    ]);
}); */


Route::middleware('auth:sanctum')->group(function () {
    // Route::get('/products', [ProductController::class, 'index']);

    Route::get('/cart', [CartController::class, 'show']);
    Route::post('/cart/items', [CartController::class, 'add']);
    Route::patch('/cart/items/{product}', [CartController::class, 'update']);
    Route::delete('/cart/items/{product}', [CartController::class, 'remove']);

    Route::post('/checkout', [CheckoutController::class, 'store']);
});

// Route::middleware('auth:sanctum')->get('/ping', fn() => 'pong');
Route::middleware('auth:sanctum')->get('/ping', fn() => response()->json(['message' => 'pong']));

Route::get('/products', [ProductController::class, 'index']);


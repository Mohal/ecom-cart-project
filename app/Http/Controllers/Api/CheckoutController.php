<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(private CartService $cartService) {}

    public function store(Request $request)
    {
        $cart = $this->cartService->getActiveCartForUser($request->user()->id);

        try {
            $order = $this->cartService->checkout($cart);
            return response()->json(['order' => $order], 201);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}

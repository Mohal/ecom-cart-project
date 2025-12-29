<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private CartService $cartService)
    {
        // To-do: Initialize
    }

    public function show(Request $request)
    {
        $cart = $this->cartService->getActiveCartForUser($request->user()->id);
        return $cart->load('items.product');
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity'   => ['nullable', 'integer', 'min:1'],
        ]);

        $cart = $this->cartService->getActiveCartForUser($request->user()->id);
        $product = Product::findOrFail($data['product_id']);
        $quantity = $data['quantity'] ?? 1;

        $cart = $this->cartService->addItem($cart, $product, $quantity);
        return response()->json($cart);
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        $cart = $this->cartService->getActiveCartForUser($request->user()->id);
        $cart = $this->cartService->updateItem($cart, $product, $data['quantity']);
        return response()->json($cart);
    }

    public function remove(Request $request, Product $product)
    {
        $cart = $this->cartService->getActiveCartForUser($request->user()->id);
        $cart = $this->cartService->removeItem($cart, $product);
        return response()->json($cart);
    }
}

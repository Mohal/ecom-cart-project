<?php

namespace App\Services;

use App\Models\{Cart, CartItem, Product, Order, OrderItem};
use Illuminate\Support\Facades\DB;
use App\Jobs\LowStockNotificationJob;

class CartService
{
    public function getActiveCartForUser(int $userId): Cart
    {
        return Cart::firstOrCreate(['user_id' => $userId, 'is_active' => true]);
    }

    public function addItem(Cart $cart, Product $product, int $quantity = 1): Cart
    {
        $item = $cart->items()->firstOrNew(['product_id' => $product->id]);
        $item->unit_price = $product->price;
        $item->quantity = ($item->exists ? $item->quantity : 0) + $quantity;
        $item->save();

        $this->checkLowStock($product);
        return $cart->fresh('items.product');
    }

    public function updateItem(Cart $cart, Product $product, int $quantity): Cart
    {
        $item = $cart->items()->where('product_id', $product->id)->firstOrFail();
        $item->quantity = max(0, $quantity);
        $item->save();

        if ($item->quantity === 0) {
            $item->delete();
        }

        $this->checkLowStock($product);
        return $cart->fresh('items.product');
    }

    public function removeItem(Cart $cart, Product $product): Cart
    {
        $cart->items()->where('product_id', $product->id)->delete();
        return $cart->fresh('items.product');
    }

    public function checkout(Cart $cart): Order
    {
        return DB::transaction(function () use ($cart) {
            $cart->load('items.product');

            foreach ($cart->items as $item) {
                if ($item->quantity > $item->product->stock_quantity) {
                    throw new \RuntimeException("Insufficient stock for {$item->product->name}");
                }
            }

            $order = Order::create([
                'user_id' => $cart->user_id,
                'total'   => $cart->subtotal(),
            ]);

            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'line_total' => $item->quantity * $item->unit_price,
                ]);

                $item->product->decrement('stock_quantity', $item->quantity);
                $this->checkLowStock($item->product);
            }

            // $cart->update(['is_active' => false]);
            $cart->delete();
            Cart::create(['user_id' => $cart->user_id, 'is_active' => true]);

            return $order->fresh('items.product');
        });
    }

    protected function checkLowStock(Product $product): void
    {
        $threshold = config('inventory.low_stock_threshold');
        if ($product->stock_quantity <= $threshold) {
            LowStockNotificationJob::dispatch($product);
        }
    }
}

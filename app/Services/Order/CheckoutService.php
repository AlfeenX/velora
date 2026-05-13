<?php

namespace App\Services\Order;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutService
{
    public function checkout(
        int $userId,
        array $data
    ): Order {

        return DB::transaction(function () use ($userId, $data) {

            $cart = Cart::with('items.variant')
                ->where('user_id', $userId)
                ->firstOrFail();

            $total = 0;

            foreach ($cart->items as $item) {

                $subtotal =
                    $item->quantity *
                    $item->variant->price;

                $total += $subtotal;
            }

            $order = Order::create([
                'user_id' => $userId,
                'address_id' => $data['address_id'],
                'order_code' => strtoupper(
                    Str::random(10)
                ),
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'total_price' => $total,
            ]);

            foreach ($cart->items as $item) {

                $subtotal =
                    $item->quantity *
                    $item->variant->price;

                $order->items()->create([
                    'product_variant_id' => $item->variant->id,
                    'quantity' => $item->quantity,
                    'price' => $item->variant->price,
                    'subtotal' => $subtotal,
                ]);

                // Reduce stock
                $item->variant->decrement(
                    'stock',
                    $item->quantity
                );
            }

            // Empty cart
            $cart->items()->delete();

            return $order->load([
                'items.variant',
                'payment',
                'shipment',
                'address'
            ]);
        });
    }
}
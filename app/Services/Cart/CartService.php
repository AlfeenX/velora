<?php

namespace App\Services\Cart;

use App\Models\Cart;
use App\Models\ProductVariant;

class CartService
{
    public function add(
        int $userId,
        int $variantId,
        int $quantity
    ): Cart {

        $variant = ProductVariant::findOrFail($variantId);

        $cart = Cart::firstOrCreate([
            'user_id' => $userId
        ]);

        $item = $cart->items()
            ->where('product_variant_id', $variantId)
            ->first();

        if ($item) {

            $item->increment('quantity', $quantity);

        } else {

            $cart->items()->create([
                'product_variant_id' => $variantId,
                'quantity' => $quantity,
            ]);
        }

        return $cart->load('items.variant');
    }
}
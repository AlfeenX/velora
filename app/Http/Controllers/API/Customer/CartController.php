<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;

use App\Services\Cart\CartService;

use App\Http\Requests\Cart\AddToCartRequest;

use App\Http\Resources\Cart\CartResource;

class CartController extends Controller
{
    public function store(
        AddToCartRequest $request,
        CartService $service
    ) {

        $cart = $service->add(
            $request->user()->id,
            $request->product_variant_id,
            $request->quantity
        );

        return new CartResource($cart);
    }
}
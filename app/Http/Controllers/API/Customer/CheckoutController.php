<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;

use App\Services\Order\CheckoutService;

use App\Http\Requests\Order\CheckoutRequest;

use App\Http\Resources\Order\OrderResource;

class CheckoutController extends Controller
{
    public function store(
        CheckoutRequest $request,
        CheckoutService $service
    ) {

        $order = $service->checkout(
            $request->user()->id,
            $request->validated()
        );

        return new OrderResource($order);
    }
}
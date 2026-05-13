<?php

namespace App\Http\Controllers\API\Customer;

use App\Models\Order;

use App\Http\Controllers\Controller;

use App\Http\Resources\Order\OrderResource;

class OrderController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $orders = Order::with([
            'items.variant',
            'payment',
            'shipment',
            'address'
        ])
        ->where('user_id', $request->user()->id)
        ->latest()
        ->paginate(10);

        return OrderResource::collection($orders);
    }

    public function show(\Illuminate\Http\Request $request, Order $order)
    {
        abort_if(
            $order->user_id !== $request->user()->id,
            403
        );

        $order->load([
            'items.variant',
            'payment',
            'shipment',
            'address'
        ]);

        return new OrderResource($order);
    }
}
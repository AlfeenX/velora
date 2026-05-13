<?php

namespace App\Http\Controllers\API\Admin;

use App\Models\Order;

use App\Http\Controllers\Controller;

use App\Http\Resources\Order\OrderResource;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with([
            'items.variant',
            'payment',
            'shipment',
            'address'
        ])
        ->latest()
        ->paginate(20);

        return OrderResource::collection($orders);
    }

    public function show(Order $order)
    {
        $order->load([
            'items.variant',
            'payment',
            'shipment',
            'address'
        ]);

        return new OrderResource($order);
    }
}
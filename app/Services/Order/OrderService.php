<?php

namespace App\Services\Order;

use App\Models\Order;

class OrderService
{
    public function updateStatus(
        Order $order,
        string $status
    ): Order {

        $order->update([
            'status' => $status
        ]);

        return $order;
    }
}
<?php

namespace App\Services\Payment;

use App\Models\Order;
use App\Models\Payment;

class PaymentService
{
    public function create(
        Order $order,
        string $method
    ): Payment {

        return Payment::create([
            'order_id' => $order->id,
            'payment_method' => $method,
            'amount' => $order->total_price,
        ]);
    }

    public function markAsPaid(
        Order $order,
        string $transactionId
    ): void {

        $order->payment()->update([
            'transaction_id' => $transactionId,
            'paid_at' => now(),
        ]);

        $order->update([
            'payment_status' => 'paid',
            'status' => 'paid',
        ]);
    }
}
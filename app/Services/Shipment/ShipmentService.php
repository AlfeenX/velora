<?php

namespace App\Services\Shipment;

use App\Models\Order;
use App\Models\Shipment;

class ShipmentService
{
    public function create(
        Order $order,
        array $data
    ): Shipment {

        return Shipment::create([
            'order_id' => $order->id,
            'courier' => $data['courier'],
            'tracking_number' => $data['tracking_number'] ?? null,
        ]);
    }

    public function markAsShipped(
        Shipment $shipment
    ): Shipment {

        $shipment->update([
            'shipped_at' => now(),
        ]);

        return $shipment;
    }

    public function markAsDelivered(
        Shipment $shipment
    ): Shipment {

        $shipment->update([
            'delivered_at' => now(),
        ]);

        return $shipment;
    }
}
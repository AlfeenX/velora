<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User\AddressResource;
use App\Http\Resources\Product\ProductVariantResource;
use App\Http\Resources\Order\PaymentResource;
use App\Http\Resources\Order\ShipmentResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'order_code' => $this->order_code,
            'status' => $this->status,
            'payment_status' => $this->payment_status,
            'total_amount' => $this->total_amount,

            'address' => new AddressResource(
                $this->whenLoaded('address')
            ),

            'items' => $this->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'subtotal' => $item->subtotal,

                    'variant' => new ProductVariantResource(
                        $item->variant
                    ),
                ];
            }),

            'payment' => new PaymentResource(
                $this->whenLoaded('payment')
            ),

            'shipment' => new ShipmentResource(
                $this->whenLoaded('shipment')
            ),

            'created_at' => $this->created_at,
        ];
    }
}

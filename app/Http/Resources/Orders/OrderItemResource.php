<?php

namespace App\Http\Resources\Orders;

use App\Http\Resources\ProductResource;
use App\Http\Resources\VendorResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
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
            'order_id' => $this->order_id,
            'product_id' => $this->product_id,
            'vendor_id' => $this->vendor_id,
            'price' => $this->price,
            'status' => $this->status,
            'created_at' => $this->created_at->format('Y-m-d H:i'),
            'product' => new ProductResource($this->whenLoaded('product')),
            'vendor' => new VendorResource($this->whenLoaded('vendor')),
        ];
    }
}

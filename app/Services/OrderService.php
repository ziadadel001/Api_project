<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Events\OrderItemCreated;

class OrderService
{
    public function createOrder($user, array $items)
    {
        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'total_price' => 0,
        ]);

        $total = 0;

        foreach ($items as $item) {
            $orderItem = $order->items()->create([
                'product_id' => $item['product_id'],
                'vendor_id' => $item['vendor_id'],
                'price' => $item['price'],
                'status' => 'pending',
            ]);

            event(new OrderItemCreated($orderItem));
            $total += $item['price'];
        }

        $order->update(['total_price' => $total]);

        return $order->load('items');
    }


    public function updateOrder(Order $order, array $data)
    {
        $order->update($data);
        return $order->fresh()->load('items');
    }

    public function deleteOrder(Order $order)
    {
        $order->items()->delete();
        $order->delete();
    }
}

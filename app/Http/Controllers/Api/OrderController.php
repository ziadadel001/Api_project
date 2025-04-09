<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\StoreOrderRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Orders\UpdateOrderRequest;
use App\Http\Resources\Orders\OrderResource;
use App\Models\Order;
use App\Services\OrderService;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    // ✅ Index
    public function index()
    {
        $orders = Order::with('items.product', 'items.vendor')->latest()->paginate(10);
        return OrderResource::collection($orders);
    }

    // ✅ Show
    public function show(Order $order)
    {
        return new OrderResource($order->load('items.product', 'items.vendor'));
    }

    // ✅ Store
    public function store(StoreOrderRequest $request)
    {
        $order = $this->orderService->createOrder(auth()->user(), $request->items);

        return new OrderResource($order->load('items.product', 'items.vendor'));
    }

    // ✅ Update
    public function update(UpdateOrderRequest $request, Order $order)
    {
        $order = $this->orderService->updateOrder($order, $request->validated());
        return new OrderResource($order);
    }

    // ✅ Delete
    public function destroy(Order $order)
    {
        $this->orderService->deleteOrder($order);
        return response()->json(['message' => 'تم حذف الطلب بنجاح']);
    }
}

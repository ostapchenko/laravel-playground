<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return OrderResource::collection(
            Order::query()->with(['customer', 'items.product'])->latest()->paginate(),
        );
    }

    public function store(StoreOrderRequest $request): OrderResource
    {
        $order = Order::create($request->validated());

        return new OrderResource($order->load(['customer', 'items.product']));
    }

    public function show(Order $order): OrderResource
    {
        return new OrderResource($order->load(['customer', 'items.product']));
    }

    public function update(UpdateOrderRequest $request, Order $order): OrderResource
    {
        $order->update($request->validated());

        return new OrderResource($order->refresh()->load(['customer', 'items.product']));
    }

    public function destroy(Order $order): Response
    {
        $order->delete();

        return response()->noContent();
    }
}

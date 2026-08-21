<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderItemRequest;
use App\Http\Requests\UpdateOrderItemRequest;
use App\Http\Resources\OrderItemResource;
use App\Models\OrderItem;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class OrderItemController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return OrderItemResource::collection(
            OrderItem::query()->with('product')->latest()->paginate(),
        );
    }

    public function store(StoreOrderItemRequest $request): OrderItemResource
    {
        $orderItem = OrderItem::create($request->validated());

        return new OrderItemResource($orderItem->load('product'));
    }

    public function show(OrderItem $orderItem): OrderItemResource
    {
        return new OrderItemResource($orderItem->load('product'));
    }

    public function update(UpdateOrderItemRequest $request, OrderItem $orderItem): OrderItemResource
    {
        $orderItem->update($request->validated());

        return new OrderItemResource($orderItem->refresh()->load('product'));
    }

    public function destroy(OrderItem $orderItem): Response
    {
        $orderItem->delete();

        return response()->noContent();
    }
}

<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Contracts\Services\OrderServiceInterface;
use App\DTOs\UpdateOrderStatusDTO;
use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\UpdateApiOrderStatusRequest;
use App\Http\Resources\Api\V1\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrderController extends Controller
{
    public function __construct(private OrderServiceInterface $orderService) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Order::with(['address', 'items.product', 'customer.user']);

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->value());
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->integer('customer_id'));
        }

        return OrderResource::collection($query->latest()->paginate(15));
    }

    public function show(Order $order): OrderResource
    {
        $order->load(['address', 'items.product', 'customer.user']);

        return new OrderResource($order);
    }

    public function updateStatus(UpdateApiOrderStatusRequest $request, Order $order): OrderResource
    {
        $dto = new UpdateOrderStatusDTO(
            status: OrderStatus::from($request->validated('status')),
        );

        $order = $this->orderService->updateStatus($order, $dto);
        $order->load(['address', 'items.product', 'customer.user']);

        return new OrderResource($order);
    }
}

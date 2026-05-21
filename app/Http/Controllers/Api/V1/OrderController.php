<?php

namespace App\Http\Controllers\Api\V1;

use App\Contracts\Services\OrderServiceInterface;
use App\DTOs\StoreOrderDTO;
use App\DTOs\StoreOrderItemDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreApiOrderRequest;
use App\Http\Resources\Api\V1\OrderResource;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct(private OrderServiceInterface $orderService) {}

    public function index(): AnonymousResourceCollection
    {
        /** @var User $user */
        $user = Auth::user();

        $orders = $user->customer->orders()
            ->with(['address', 'items.product'])
            ->latest()
            ->paginate(15);

        return OrderResource::collection($orders);
    }

    public function show(int $id): OrderResource
    {
        /** @var User $user */
        $user = Auth::user();

        $order = $this->orderService->getOrderForCustomer($id, $user->customer->id);
        $order->load(['address', 'items.product']);

        return new OrderResource($order);
    }

    public function store(StoreApiOrderRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $items = collect($request->validated('items'))->map(function (array $item): StoreOrderItemDTO {
            $product = Product::findOrFail($item['product_id']);

            return new StoreOrderItemDTO(
                product_id: $product->id,
                quantity: $item['quantity'],
                unit_price: $product->price,
            );
        })->all();

        $dto = new StoreOrderDTO(
            customer_id: $user->customer->id,
            address_id: $request->integer('address_id'),
            items: $items,
            notes: $request->string('notes')->value() ?: null,
        );

        $order = $this->orderService->placeOrder($dto);
        $order->load(['address', 'items.product']);

        return (new OrderResource($order))->response()->setStatusCode(201);
    }
}

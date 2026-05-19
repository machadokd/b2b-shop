<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\Services\OrderServiceInterface;
use App\DTOs\UpdateOrderStatusDTO;
use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateOrderStatusRequest;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(private OrderServiceInterface $orderService) {}

    public function index(Request $request): View
    {
        $query = Order::with(['customer.user', 'address']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->integer('customer_id'));
        }

        $orders = $query->latest()->paginate(15)->withQueryString();
        $customers = Customer::with('user')->orderBy('company_name')->get();
        $statuses = OrderStatus::cases();

        return view('admin.orders.index', compact('orders', 'customers', 'statuses'));
    }

    public function show(Order $order): View
    {
        $order->load(['customer.user', 'address', 'items.product']);
        $statuses = $order->status->allowedTransitions();

        return view('admin.orders.show', compact('order', 'statuses'));
    }

    public function updateStatus(UpdateOrderStatusRequest $request, Order $order): RedirectResponse
    {
        $dto = new UpdateOrderStatusDTO(
            status: OrderStatus::from($request->validated('status')),
        );

        $this->orderService->updateStatus($order, $dto);

        return back()->with('success', 'Estado da encomenda atualizado com sucesso.');
    }
}

<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $customerId = $user->customer?->id;

        $orders = Order::with(['address'])
            ->where('customer_id', $customerId)
            ->latest()
            ->paginate(15);

        return view('shop.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        /** @var User $user */
        $user = Auth::user();
        $this->authorize('view', $order);

        $order->load(['address', 'items.product']);

        return view('shop.orders.show', compact('order'));
    }
}

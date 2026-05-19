<?php

namespace App\Http\Controllers\Shop;

use App\Enums\OrderStatus;
use App\Exceptions\InsufficientStockException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\StoreOrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function show(): View|RedirectResponse
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('shop.cart.index')->with('error', 'O carrinho está vazio.');
        }

        /** @var User $user */
        $user = Auth::user();
        $addresses = $user->customer ? $user->customer->addresses : collect();

        return view('shop.checkout.show', compact('cart', 'addresses'));
    }

    public function store(StoreOrderRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $customer = $user->customer;
        $cart = session('cart', []);

        // Guard clause: validate stock before opening transaction
        foreach ($cart as $item) {
            $product = Product::find($item['product_id']);

            if (! $product || $product->stock < $item['quantity']) {
                throw new InsufficientStockException(
                    $product ? $product->name : 'Produto',
                    $item['quantity'],
                    $product ? $product->stock : 0,
                );
            }
        }

        $orderId = DB::transaction(function () use ($customer, $cart, $request) {
            $total = collect($cart)->sum(fn ($i) => $i['price'] * $i['quantity']);

            /** @var Order $order */
            $order = Order::create([
                'customer_id' => $customer->id,
                'address_id' => $request->integer('address_id'),
                'status' => OrderStatus::Pending,
                'total' => $total,
            ]);

            foreach ($cart as $item) {
                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                ]);

                // Stock decremented synchronously; Bloco 8 replaces with UpdateProductStock job
                Product::where('id', $item['product_id'])->decrement('stock', $item['quantity']);
            }

            session()->forget('cart');

            // Bloco 8: OrderPlaced::dispatch($order);

            return $order->id;
        });

        return redirect()->route('shop.orders.show', $orderId)->with('success', 'Encomenda criada com sucesso!');
    }
}

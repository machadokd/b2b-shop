<?php

namespace App\Http\Controllers\Shop;

use App\Exceptions\InsufficientStockException;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $cart = session('cart', []);

        return view('shop.cart.index', compact('cart'));
    }

    public function add(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->is_active, 404);

        $quantity = max(1, (int) $request->input('quantity', 1));
        $cart = session('cart', []);

        $currentQty = $cart[$product->id]['quantity'] ?? 0;
        $newQty = $currentQty + $quantity;

        if ($product->stock < $newQty) {
            throw new InsufficientStockException($product->name, $newQty, $product->stock);
        }

        $cart[$product->id] = [
            'product_id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $newQty,
            'stock' => $product->stock,
            'image' => $product->image,
        ];

        session(['cart' => $cart]);

        return back()->with('success', "'{$product->name}' adicionado ao carrinho.");
    }

    public function update(Request $request, int $productId): RedirectResponse
    {
        $quantity = max(1, (int) $request->input('quantity', 1));
        $cart = session('cart', []);

        if (isset($cart[$productId])) {
            $product = Product::find($productId);

            if ($product && $product->stock < $quantity) {
                throw new InsufficientStockException($product->name, $quantity, $product->stock);
            }

            $cart[$productId]['quantity'] = $quantity;
            session(['cart' => $cart]);
        }

        return back()->with('success', 'Carrinho atualizado.');
    }

    public function remove(int $productId): RedirectResponse
    {
        $cart = session('cart', []);
        unset($cart[$productId]);
        session(['cart' => $cart]);

        return back()->with('success', 'Produto removido do carrinho.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\Services\ProductServiceInterface;
use App\DTOs\StoreProductDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(private ProductServiceInterface $productService) {}

    public function index(Request $request): View
    {
        $query = Product::with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->integer('category_id'));
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->input('status') === 'active');
        }

        $products = $query->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $image = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('products', 'public');
        }

        $dto = new StoreProductDTO(
            category_id: (int) $data['category_id'],
            name: $data['name'],
            slug: Str::slug($data['name']),
            sku: $data['sku'],
            price: (float) $data['price'],
            stock: (int) $data['stock'],
            description: $data['description'] ?? null,
            image: $image,
            is_active: $request->boolean('is_active', true),
        );

        $this->productService->store($dto);

        return redirect()->route('admin.products.index')->with('success', 'Produto criado com sucesso.');
    }

    public function edit(Product $product): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $data = $request->validated();
        $image = null;

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $image = $request->file('image')->store('products', 'public');
        }

        $dto = new StoreProductDTO(
            category_id: (int) $data['category_id'],
            name: $data['name'],
            slug: Str::slug($data['name']),
            sku: $data['sku'],
            price: (float) $data['price'],
            stock: (int) $data['stock'],
            description: $data['description'] ?? null,
            image: $image,
            is_active: $request->boolean('is_active', true),
        );

        $this->productService->update($product, $dto);

        return redirect()->route('admin.products.index')->with('success', 'Produto atualizado com sucesso.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $this->productService->delete($product);

        return redirect()->route('admin.products.index')->with('success', 'Produto eliminado com sucesso.');
    }

    public function toggleActive(Product $product): RedirectResponse
    {
        $this->productService->toggleActive($product);

        return back()->with('success', 'Estado do produto atualizado.');
    }
}

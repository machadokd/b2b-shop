<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCatalogRequest;
use App\Http\Requests\Admin\UpdateCatalogRequest;
use App\Models\Catalog;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function index(): View
    {
        $catalogs = Catalog::withCount('products')->paginate(15);

        return view('admin.catalogs.index', compact('catalogs'));
    }

    public function create(): View
    {
        $products = Product::active()->orderBy('name')->get();

        return view('admin.catalogs.create', compact('products'));
    }

    public function store(StoreCatalogRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $catalog = Catalog::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'description' => $data['description'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        if (! empty($data['products'])) {
            $catalog->products()->sync($data['products']);
        }

        return redirect()->route('admin.catalogs.index')->with('success', 'Catálogo criado com sucesso.');
    }

    public function edit(Catalog $catalog): View
    {
        $products = Product::active()->orderBy('name')->get();
        $catalog->load('products');

        return view('admin.catalogs.edit', compact('catalog', 'products'));
    }

    public function update(UpdateCatalogRequest $request, Catalog $catalog): RedirectResponse
    {
        $data = $request->validated();
        $catalog->update([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'description' => $data['description'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        $catalog->products()->sync($data['products'] ?? []);

        return redirect()->route('admin.catalogs.index')->with('success', 'Catálogo atualizado com sucesso.');
    }

    public function destroy(Catalog $catalog): RedirectResponse
    {
        $catalog->delete();

        return redirect()->route('admin.catalogs.index')->with('success', 'Catálogo eliminado com sucesso.');
    }

    public function toggleActive(Catalog $catalog): RedirectResponse
    {
        $catalog->update(['is_active' => ! $catalog->is_active]);

        return back()->with('success', 'Estado do catálogo atualizado.');
    }
}

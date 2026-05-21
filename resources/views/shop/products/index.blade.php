@extends('layouts.shop')

@section('title', 'Produtos')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h1 class="page-title mb-0">Catálogo de Produtos</h1>
</div>

<form method="GET" class="filter-card mb-4 p-3">
    <div class="row g-2 align-items-end">
        <div class="col-12 col-sm">
            <label class="form-label small fw-medium mb-1">Pesquisa</label>
            <input type="text" name="search" class="form-control form-control-sm"
                   placeholder="Nome ou SKU..." value="{{ request('search') }}">
        </div>
        <div class="col-12 col-sm-auto">
            <label class="form-label small fw-medium mb-1">Categoria</label>
            <select name="category_id" class="form-select form-select-sm" style="min-width:160px;">
                <option value="">Todas as categorias</option>
                @foreach ($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-sm-auto d-flex gap-2">
            <button class="btn btn-primary btn-sm px-3">Filtrar</button>
            @if (request('search') || request('category_id'))
                <a href="{{ route('shop.products.index') }}" class="btn btn-outline-secondary btn-sm">Limpar</a>
            @endif
        </div>
    </div>
</form>

<div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
    @forelse ($products as $product)
    <div class="col">
        <div class="card product-card h-100">
            @if ($product->image)
                <img src="{{ Storage::url($product->image) }}" class="card-img-top"
                     alt="{{ $product->name }}" style="height:200px;object-fit:cover;">
            @else
                <div class="img-placeholder">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                        <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z"/>
                    </svg>
                </div>
            @endif

            <div class="card-body d-flex flex-column p-3">
                <span class="category-label mb-1">{{ $product->category?->name ?? '—' }}</span>
                <h6 class="card-title mb-1 fw-semibold" style="font-size:.9rem;line-height:1.3;">
                    {{ $product->name }}
                </h6>
                <p class="text-muted mb-2" style="font-size:.75rem;"><code>{{ $product->sku }}</code></p>

                <div class="mt-auto">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="price">{{ number_format($product->price, 2) }} €</span>
                        @if ($product->stock === 0)
                            <span class="badge badge-stock-out" style="font-size:.7rem;">Esgotado</span>
                        @elseif ($product->stock <= 5)
                            <span class="badge badge-stock-low" style="font-size:.7rem;">{{ $product->stock }} un.</span>
                        @else
                            <span class="badge badge-stock-ok" style="font-size:.7rem;">Em stock</span>
                        @endif
                    </div>
                    <a href="{{ route('shop.products.show', $product) }}"
                       class="btn btn-outline-primary btn-sm w-100">
                        Ver detalhes
                    </a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z"/>
            </svg>
            <p>Nenhum produto encontrado.</p>
            <a href="{{ route('shop.products.index') }}" class="btn btn-outline-primary btn-sm">
                Ver todos os produtos
            </a>
        </div>
    </div>
    @endforelse
</div>

<div class="mt-4">{{ $products->links() }}</div>
@endsection

@extends('layouts.shop')

@section('title', 'Produtos')

@section('content')
<h4 class="mb-4">Catálogo de Produtos</h4>

<form method="GET" class="card mb-4 shadow-sm">
    <div class="card-body d-flex gap-3 flex-wrap align-items-end py-3">
        <div class="flex-grow-1">
            <label class="form-label small mb-1">Pesquisa</label>
            <input type="text" name="search" class="form-control form-control-sm"
                   placeholder="Nome ou SKU..." value="{{ request('search') }}">
        </div>
        <div>
            <label class="form-label small mb-1">Categoria</label>
            <select name="category_id" class="form-select form-select-sm">
                <option value="">Todas</option>
                @foreach ($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
                @endforeach
            </select>
        </div>
        <button class="btn btn-primary btn-sm">Filtrar</button>
        <a href="{{ route('shop.products.index') }}" class="btn btn-outline-secondary btn-sm">Limpar</a>
    </div>
</form>

<div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
    @forelse ($products as $product)
    <div class="col">
        <div class="card h-100 shadow-sm">
            @if ($product->image)
            <img src="{{ Storage::url($product->image) }}" class="card-img-top" alt="{{ $product->name }}"
                 style="height:180px;object-fit:cover;">
            @else
            <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height:180px;">
                <span class="opacity-50">Sem imagem</span>
            </div>
            @endif
            <div class="card-body d-flex flex-column">
                <p class="text-muted small mb-1">{{ $product->category?->name }}</p>
                <h6 class="card-title">{{ $product->name }}</h6>
                <p class="text-muted small mb-2"><code>{{ $product->sku }}</code></p>
                <div class="mt-auto">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold text-primary fs-5">{{ number_format($product->price, 2) }} €</span>
                        <span class="text-muted small">Stock: {{ $product->stock }}</span>
                    </div>
                    <a href="{{ route('shop.products.show', $product) }}" class="btn btn-outline-primary btn-sm w-100">
                        Ver Detalhes
                    </a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <p class="text-center text-muted py-5">Nenhum produto encontrado.</p>
    </div>
    @endforelse
</div>

<div class="mt-4">{{ $products->links() }}</div>
@endsection

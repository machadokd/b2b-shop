@extends('layouts.shop')

@section('title', $product->name)

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('shop.products.index') }}">Produtos</a></li>
        <li class="breadcrumb-item text-muted">{{ $product->category?->name }}</li>
        <li class="breadcrumb-item active">{{ $product->name }}</li>
    </ol>
</nav>

<div class="row g-4">
    <div class="col-md-5">
        @if ($product->image)
        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
             class="img-fluid rounded shadow-sm">
        @else
        <div class="bg-secondary text-white d-flex align-items-center justify-content-center rounded"
             style="height:320px;">
            <span class="opacity-50 fs-4">Sem imagem</span>
        </div>
        @endif
    </div>

    <div class="col-md-7">
        <p class="text-muted mb-1">{{ $product->category?->name }} &bull; <code>{{ $product->sku }}</code></p>
        <h2>{{ $product->name }}</h2>

        @if ($product->description)
        <p class="text-muted">{{ $product->description }}</p>
        @endif

        <div class="d-flex align-items-baseline gap-3 my-4">
            <span class="fs-2 fw-bold text-primary">{{ number_format($product->price, 2) }} €</span>
            <span class="text-{{ $product->stock > 0 ? 'success' : 'danger' }}">
                {{ $product->stock > 0 ? "Stock: {$product->stock}" : 'Esgotado' }}
            </span>
        </div>

        <form action="{{ route('shop.cart.add', $product) }}" method="POST" class="d-flex gap-2">
            @csrf
            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}"
                   class="form-control" style="width:80px;"
                   {{ $product->stock === 0 ? 'disabled' : '' }}>
            <button type="submit" class="btn btn-primary"
                    {{ $product->stock === 0 ? 'disabled' : '' }}>
                Adicionar ao Carrinho
            </button>
        </form>
    </div>
</div>
@endsection

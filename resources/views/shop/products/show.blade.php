@extends('layouts.shop')

@section('title', $product->name)

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('shop.products.index') }}" class="text-decoration-none">Produtos</a>
        </li>
        @if ($product->category)
            <li class="breadcrumb-item text-muted">{{ $product->category->name }}</li>
        @endif
        <li class="breadcrumb-item active fw-medium">{{ $product->name }}</li>
    </ol>
</nav>

<div class="row g-4 g-lg-5">
    <div class="col-md-5">
        @if ($product->image)
            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                 class="img-fluid rounded-3 shadow-sm w-100"
                 style="object-fit:cover;max-height:420px;">
        @else
            <div class="rounded-3 d-flex align-items-center justify-content-center"
                 style="height:320px;background:linear-gradient(135deg,#e2e8f0 0%,#cbd5e1 100%);">
                <svg xmlns="http://www.w3.org/2000/svg" width="72" height="72" fill="#94a3b8" viewBox="0 0 16 16">
                    <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                    <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z"/>
                </svg>
            </div>
        @endif
    </div>

    <div class="col-md-7">
        <div class="d-flex align-items-center gap-2 mb-2">
            @if ($product->category)
                <span class="badge" style="background:#e0f2fe;color:#0369a1;font-weight:600;font-size:.72rem;">
                    {{ $product->category->name }}
                </span>
            @endif
            <code class="text-muted" style="font-size:.8rem;">{{ $product->sku }}</code>
        </div>

        <h1 class="fw-bold mb-3" style="font-size:1.6rem;line-height:1.25;">{{ $product->name }}</h1>

        @if ($product->description)
            <p class="text-muted mb-4" style="line-height:1.7;">{{ $product->description }}</p>
        @endif

        <div class="d-flex align-items-center gap-3 mb-4 p-3 rounded-3" style="background:#f8fafc;border:1px solid #e2e8f0;">
            <div>
                <div class="text-muted" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;font-weight:600;">Preço</div>
                <div style="font-size:1.9rem;font-weight:700;color:var(--brand-primary);line-height:1.1;">
                    {{ number_format($product->price, 2) }} €
                </div>
            </div>
            <div class="border-start ps-3">
                <div class="text-muted" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;font-weight:600;">Disponibilidade</div>
                @if ($product->stock === 0)
                    <span class="badge badge-stock-out" style="font-size:.8rem;padding:.35rem .65rem;">Esgotado</span>
                @elseif ($product->stock <= 5)
                    <span class="badge badge-stock-low" style="font-size:.8rem;padding:.35rem .65rem;">Últimas {{ $product->stock }} unidades</span>
                @else
                    <span class="badge badge-stock-ok" style="font-size:.8rem;padding:.35rem .65rem;">Em stock ({{ $product->stock }} un.)</span>
                @endif
            </div>
        </div>

        <form action="{{ route('shop.cart.add', $product) }}" method="POST">
            @csrf
            <div class="d-flex gap-2 align-items-center">
                <div>
                    <label class="form-label small fw-medium mb-1">Quantidade</label>
                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}"
                           class="form-control text-center fw-semibold"
                           style="width:90px;"
                           {{ $product->stock === 0 ? 'disabled' : '' }}>
                </div>
                <div class="pt-4">
                    <button type="submit"
                            class="btn btn-primary btn-lg fw-semibold"
                            style="padding:.65rem 1.75rem;"
                            {{ $product->stock === 0 ? 'disabled' : '' }}>
                        @if ($product->stock === 0)
                            Produto Esgotado
                        @else
                            Adicionar ao Carrinho
                        @endif
                    </button>
                </div>
            </div>
        </form>

        <div class="mt-3">
            <a href="{{ route('shop.products.index') }}" class="text-muted text-decoration-none"
               style="font-size:.875rem;">
                ← Voltar ao catálogo
            </a>
        </div>
    </div>
</div>
@endsection

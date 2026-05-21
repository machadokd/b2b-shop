@extends('layouts.shop')

@section('title', 'Carrinho')

@section('content')
<h1 class="page-title">Carrinho de Compras</h1>

@if (empty($cart))
    <div class="empty-state">
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
            <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
        </svg>
        <p>O carrinho está vazio.</p>
        <a href="{{ route('shop.products.index') }}" class="btn btn-primary">Ver Produtos</a>
    </div>
@else
<div class="row g-4">
    <div class="col-lg-8">

        {{-- Desktop table --}}
        <div class="card d-none d-md-block">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th class="text-center" style="width:140px;">Quantidade</th>
                            <th class="text-end">Preço Unit.</th>
                            <th class="text-end">Subtotal</th>
                            <th style="width:48px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cart as $item)
                        <tr>
                            <td class="fw-medium">{{ $item['name'] }}</td>
                            <td class="text-center">
                                <form action="{{ route('shop.cart.update', $item['product_id']) }}" method="POST"
                                      class="d-flex justify-content-center align-items-center gap-1">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}"
                                           min="1" max="{{ $item['stock'] }}"
                                           class="form-control form-control-sm text-center"
                                           style="width:64px;">
                                    <button class="btn btn-outline-secondary btn-sm px-2" title="Actualizar">✓</button>
                                </form>
                            </td>
                            <td class="text-end text-muted">{{ number_format($item['price'], 2) }} €</td>
                            <td class="text-end fw-semibold">{{ number_format($item['price'] * $item['quantity'], 2) }} €</td>
                            <td class="text-end">
                                <form action="{{ route('shop.cart.remove', $item['product_id']) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm px-2" title="Remover">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                            <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Mobile cards --}}
        <div class="d-flex flex-column gap-2 d-md-none">
            @foreach ($cart as $item)
            <div class="cart-item-card">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="fw-semibold" style="font-size:.9rem;">{{ $item['name'] }}</div>
                    <form action="{{ route('shop.cart.remove', $item['product_id']) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm px-2 py-1">×</button>
                    </form>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <form action="{{ route('shop.cart.update', $item['product_id']) }}" method="POST"
                          class="d-flex align-items-center gap-1">
                        @csrf
                        @method('PATCH')
                        <label class="text-muted me-1" style="font-size:.8rem;">Qtd:</label>
                        <input type="number" name="quantity" value="{{ $item['quantity'] }}"
                               min="1" max="{{ $item['stock'] }}"
                               class="form-control form-control-sm text-center" style="width:60px;">
                        <button class="btn btn-outline-secondary btn-sm px-2">✓</button>
                    </form>
                    <div class="text-end">
                        <div class="text-muted" style="font-size:.75rem;">{{ number_format($item['price'], 2) }} € × {{ $item['quantity'] }}</div>
                        <div class="fw-bold">{{ number_format($item['price'] * $item['quantity'], 2) }} €</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card" style="position:sticky;top:80px;">
            <div class="card-header">Resumo do pedido</div>
            <div class="card-body">
                <div class="d-flex justify-content-between text-muted mb-2" style="font-size:.875rem;">
                    <span>Artigos ({{ collect($cart)->sum('quantity') }})</span>
                    <span>{{ number_format(collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']), 2) }} €</span>
                </div>
                <div class="d-flex justify-content-between fw-bold fs-5 border-top pt-3 mt-2">
                    <span>Total</span>
                    <span>{{ number_format(collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']), 2) }} €</span>
                </div>
                <div class="d-grid gap-2 mt-3">
                    <a href="{{ route('shop.checkout.show') }}" class="btn btn-success fw-semibold">
                        Finalizar Compra
                    </a>
                    <a href="{{ route('shop.products.index') }}" class="btn btn-outline-secondary">
                        Continuar a Comprar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

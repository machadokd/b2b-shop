@extends('layouts.shop')

@section('title', 'Carrinho')

@section('content')
<h4 class="mb-4">Carrinho de Compras</h4>

@if (empty($cart))
<div class="text-center py-5">
    <p class="text-muted fs-5">O carrinho está vazio.</p>
    <a href="{{ route('shop.products.index') }}" class="btn btn-primary">Ver Produtos</a>
</div>
@else
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Produto</th>
                            <th class="text-center">Quantidade</th>
                            <th class="text-end">Preço Unit.</th>
                            <th class="text-end">Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cart as $item)
                        <tr>
                            <td>
                                <div class="fw-medium">{{ $item['name'] }}</div>
                            </td>
                            <td class="text-center">
                                <form action="{{ route('shop.cart.update', $item['product_id']) }}" method="POST" class="d-flex justify-content-center align-items-center gap-1">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}"
                                           min="1" max="{{ $item['stock'] }}"
                                           class="form-control form-control-sm text-center" style="width:70px;">
                                    <button class="btn btn-outline-secondary btn-sm">✓</button>
                                </form>
                            </td>
                            <td class="text-end">{{ number_format($item['price'], 2) }} €</td>
                            <td class="text-end fw-bold">{{ number_format($item['price'] * $item['quantity'], 2) }} €</td>
                            <td class="text-end">
                                <form action="{{ route('shop.cart.remove', $item['product_id']) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm">×</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="mb-3">Resumo</h5>
                <div class="d-flex justify-content-between mb-2">
                    <span>Itens</span>
                    <span>{{ collect($cart)->sum('quantity') }}</span>
                </div>
                <div class="d-flex justify-content-between fw-bold fs-5 border-top pt-2">
                    <span>Total</span>
                    <span>{{ number_format(collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']), 2) }} €</span>
                </div>
                <a href="{{ route('shop.checkout.show') }}" class="btn btn-success w-100 mt-3">
                    Finalizar Compra
                </a>
                <a href="{{ route('shop.products.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                    Continuar a Comprar
                </a>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

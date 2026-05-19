@extends('layouts.shop')

@section('title', 'Finalizar Compra')

@section('content')
<h4 class="mb-4">Finalizar Compra</h4>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('shop.checkout.store') }}" method="POST">
                    @csrf
                    <h5 class="mb-3">Morada de Entrega</h5>

                    @if ($addresses->isEmpty())
                    <div class="alert alert-warning">
                        Não tem moradas registadas. Contacte o suporte para adicionar uma morada.
                    </div>
                    @else
                    @foreach ($addresses as $address)
                    <div class="form-check mb-3 border rounded p-3 {{ $loop->first ? 'border-primary' : '' }}">
                        <input class="form-check-input" type="radio" name="address_id"
                               id="address_{{ $address->id }}" value="{{ $address->id }}"
                               {{ ($address->is_default || $loop->first) && !old('address_id') ? 'checked' : '' }}
                               {{ old('address_id') == $address->id ? 'checked' : '' }}>
                        <label class="form-check-label" for="address_{{ $address->id }}">
                            <strong>{{ $address->recipient_name }}</strong>
                            @if ($address->is_default)
                            <span class="badge bg-primary ms-1">Predefinida</span>
                            @endif
                            <br>
                            <span class="text-muted small">
                                {{ $address->address_line }}, {{ $address->postal_code }} {{ $address->city }}, {{ $address->country }}
                            </span>
                        </label>
                    </div>
                    @endforeach
                    @error('address_id')
                    <div class="text-danger small">{{ $message }}</div>
                    @enderror

                    <button type="submit" class="btn btn-success w-100 mt-3">
                        Confirmar Encomenda
                    </button>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card shadow-sm">
            <div class="card-header">Resumo do Pedido</div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <tbody>
                        @foreach ($cart as $item)
                        <tr>
                            <td>{{ $item['name'] }} <span class="text-muted">×{{ $item['quantity'] }}</span></td>
                            <td class="text-end">{{ number_format($item['price'] * $item['quantity'], 2) }} €</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td class="fw-bold">Total</td>
                            <td class="text-end fw-bold">
                                {{ number_format(collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']), 2) }} €
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <a href="{{ route('shop.cart.index') }}" class="btn btn-outline-secondary w-100 mt-2">
            ← Voltar ao Carrinho
        </a>
    </div>
</div>
@endsection

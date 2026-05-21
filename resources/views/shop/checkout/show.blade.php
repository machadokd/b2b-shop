@extends('layouts.shop')

@section('title', 'Finalizar Compra')

@section('content')
<h1 class="page-title">Finalizar Compra</h1>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                    <path d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A31.493 31.493 0 0 1 8 14.58a31.481 31.481 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94zM8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10z"/>
                    <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                </svg>
                Morada de Entrega
            </div>
            <div class="card-body">
                <form action="{{ route('shop.checkout.store') }}" method="POST">
                    @csrf

                    @if ($addresses->isEmpty())
                        <div class="alert alert-warning d-flex gap-2 align-items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="flex-shrink-0 mt-1" viewBox="0 0 16 16">
                                <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                            </svg>
                            <div>
                                <strong>Sem morada registada</strong><br>
                                <span class="text-muted" style="font-size:.875rem;">Contacte o suporte para adicionar uma morada à sua conta.</span>
                            </div>
                        </div>
                    @else
                        <div class="d-flex flex-column gap-2 mb-3">
                            @foreach ($addresses as $address)
                            <label class="address-option" for="address_{{ $address->id }}">
                                <div class="d-flex align-items-start gap-3">
                                    <input class="form-check-input mt-1 flex-shrink-0" type="radio"
                                           name="address_id"
                                           id="address_{{ $address->id }}"
                                           value="{{ $address->id }}"
                                           {{ ($address->is_default || $loop->first) && !old('address_id') ? 'checked' : '' }}
                                           {{ old('address_id') == $address->id ? 'checked' : '' }}>
                                    <div>
                                        <div class="fw-semibold" style="font-size:.9rem;">
                                            {{ $address->recipient_name }}
                                            @if ($address->is_default)
                                                <span class="badge bg-primary ms-1" style="font-size:.7rem;">Predefinida</span>
                                            @endif
                                        </div>
                                        <div class="text-muted" style="font-size:.825rem;line-height:1.5;">
                                            {{ $address->address_line }}<br>
                                            {{ $address->postal_code }} {{ $address->city }} — {{ $address->country }}
                                        </div>
                                    </div>
                                </div>
                            </label>
                            @endforeach
                        </div>

                        @error('address_id')
                            <div class="text-danger small mb-2">{{ $message }}</div>
                        @enderror

                        <button type="submit" class="btn btn-success w-100 fw-semibold" style="padding:.65rem;">
                            Confirmar Encomenda
                        </button>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card" style="position:sticky;top:80px;">
            <div class="card-header">Resumo do Pedido</div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @foreach ($cart as $item)
                    <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                        <div>
                            <div style="font-size:.875rem;font-weight:500;">{{ $item['name'] }}</div>
                            <div class="text-muted" style="font-size:.775rem;">{{ $item['quantity'] }} × {{ number_format($item['price'], 2) }} €</div>
                        </div>
                        <span class="fw-semibold" style="font-size:.875rem;">
                            {{ number_format($item['price'] * $item['quantity'], 2) }} €
                        </span>
                    </li>
                    @endforeach
                </ul>
                <div class="px-4 py-3 border-top">
                    <div class="d-flex justify-content-between fw-bold fs-5">
                        <span>Total</span>
                        <span>{{ number_format(collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']), 2) }} €</span>
                    </div>
                </div>
            </div>
        </div>
        <a href="{{ route('shop.cart.index') }}" class="btn btn-outline-secondary w-100 mt-2">
            ← Voltar ao Carrinho
        </a>
    </div>
</div>
@endsection

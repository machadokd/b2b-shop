@extends('layouts.shop')

@section('title', 'As Minhas Encomendas')

@section('content')
<h1 class="page-title">As Minhas Encomendas</h1>

@if ($orders->isEmpty())
    <div class="empty-state">
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
            <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
        </svg>
        <p>Ainda não tem encomendas.</p>
        <a href="{{ route('shop.products.index') }}" class="btn btn-primary">Começar a Comprar</a>
    </div>
@else

{{-- Desktop table --}}
<div class="card d-none d-md-block">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th style="width:80px;">#</th>
                    <th>Estado</th>
                    <th>Morada</th>
                    <th class="text-end">Total</th>
                    <th>Data</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                <tr>
                    <td class="text-muted fw-medium">{{ $order->id }}</td>
                    <td>
                        <span class="badge bg-{{ $order->status->badgeClass() }}" style="padding:.35rem .65rem;font-size:.775rem;">
                            {{ $order->status->label() }}
                        </span>
                    </td>
                    <td class="text-muted" style="font-size:.875rem;">{{ $order->address?->city ?? '—' }}</td>
                    <td class="text-end fw-semibold">{{ number_format($order->total, 2) }} €</td>
                    <td class="text-muted" style="font-size:.825rem;">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td class="text-end">
                        <a href="{{ route('shop.orders.show', $order) }}"
                           class="btn btn-outline-primary btn-sm">Ver detalhes</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Mobile cards --}}
<div class="d-flex flex-column gap-2 d-md-none">
    @foreach ($orders as $order)
    <a href="{{ route('shop.orders.show', $order) }}" class="text-decoration-none">
        <div class="card" style="transition:box-shadow 180ms;">
            <div class="card-body py-3 px-3">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <span class="fw-semibold" style="font-size:.875rem;">Encomenda #{{ $order->id }}</span>
                    <span class="badge bg-{{ $order->status->badgeClass() }}" style="font-size:.72rem;">
                        {{ $order->status->label() }}
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted" style="font-size:.8rem;">{{ $order->created_at->format('d/m/Y') }}</span>
                    <span class="fw-bold" style="color:var(--brand-primary);">{{ number_format($order->total, 2) }} €</span>
                </div>
            </div>
        </div>
    </a>
    @endforeach
</div>

<div class="mt-4">{{ $orders->links() }}</div>
@endif
@endsection

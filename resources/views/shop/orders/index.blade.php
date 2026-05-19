@extends('layouts.shop')

@section('title', 'As Minhas Encomendas')

@section('content')
<h4 class="mb-4">As Minhas Encomendas</h4>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Estado</th>
                    <th>Morada</th>
                    <th>Total</th>
                    <th>Data</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>
                        <span class="badge bg-{{ $order->status->badgeClass() }}">
                            {{ $order->status->label() }}
                        </span>
                    </td>
                    <td>{{ $order->address?->city }}</td>
                    <td>{{ number_format($order->total, 2) }} €</td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td class="text-end">
                        <a href="{{ route('shop.orders.show', $order) }}" class="btn btn-outline-primary btn-sm">Ver</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                        Ainda não tem encomendas.
                        <a href="{{ route('shop.products.index') }}">Começa a comprar agora!</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $orders->links() }}</div>
@endsection

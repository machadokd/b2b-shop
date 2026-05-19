@extends('layouts.shop')

@section('title', 'Encomenda #' . $order->id)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Encomenda #{{ $order->id }}</h4>
    <a href="{{ route('shop.orders.index') }}" class="btn btn-outline-secondary btn-sm">← Voltar</a>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h6 class="card-title text-muted">Estado</h6>
                <span id="order-status-badge" class="badge bg-{{ $order->status->badgeClass() }} fs-6">
                    {{ $order->status->label() }}
                </span>
                <hr>
                <dl class="mb-0 small">
                    <dt>Data</dt>
                    <dd>{{ $order->created_at->format('d/m/Y H:i') }}</dd>
                    <dt>Total</dt>
                    <dd class="fw-bold">{{ number_format($order->total, 2) }} €</dd>
                </dl>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="card-title text-muted">Morada de Entrega</h6>
                @if ($order->address)
                <address class="mb-0 small">
                    <strong>{{ $order->address->recipient_name }}</strong><br>
                    {{ $order->address->address_line }}<br>
                    {{ $order->address->postal_code }} {{ $order->address->city }}<br>
                    {{ $order->address->country }}
                </address>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header">Itens</div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Produto</th>
                            <th class="text-center">Qtd</th>
                            <th class="text-end">Preço Unit.</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                        <tr>
                            <td>{{ $item->product?->name ?? '—' }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end">{{ number_format($item->unit_price, 2) }} €</td>
                            <td class="text-end">{{ number_format($item->unit_price * $item->quantity, 2) }} €</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Total</td>
                            <td class="text-end fw-bold">{{ number_format($order->total, 2) }} €</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Bloco 9: Socket.IO listener para atualização em tempo real --}}
<script>
// Echo.private(`orders.{{ $order->id }}`)
//     .listen('.OrderStatusChanged', (e) => {
//         document.getElementById('order-status-badge').textContent = e.status_label;
//     });
</script>
@endpush

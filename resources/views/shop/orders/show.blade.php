@extends('layouts.shop')

@section('title', 'Encomenda #' . $order->id)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title mb-0">Encomenda <span class="text-muted fw-normal">#{{ $order->id }}</span></h1>
    <a href="{{ route('shop.orders.index') }}" class="btn btn-outline-secondary btn-sm">← Voltar</a>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header">Estado do Pedido</div>
            <div class="card-body">
                <span id="order-status-badge"
                      class="badge bg-{{ $order->status->badgeClass() }} status-large">
                    {{ $order->status->label() }}
                </span>
                <div class="mt-3 d-flex flex-column gap-2">
                    <div class="d-flex justify-content-between" style="font-size:.875rem;">
                        <span class="text-muted">Data</span>
                        <span class="fw-medium">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="d-flex justify-content-between" style="font-size:.875rem;">
                        <span class="text-muted">Total</span>
                        <span class="fw-bold" style="color:var(--brand-primary);">{{ number_format($order->total, 2) }} €</span>
                    </div>
                </div>
            </div>
        </div>

        @if ($order->address)
        <div class="card">
            <div class="card-header">Morada de Entrega</div>
            <div class="card-body">
                <address class="mb-0" style="font-size:.875rem;line-height:1.7;font-style:normal;">
                    <strong>{{ $order->address->recipient_name }}</strong><br>
                    {{ $order->address->address_line }}<br>
                    {{ $order->address->postal_code }} {{ $order->address->city }}<br>
                    {{ $order->address->country }}
                </address>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Artigos</div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th class="text-center" style="width:70px;">Qtd</th>
                            <th class="text-end">Preço Unit.</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                        <tr>
                            <td class="fw-medium">{{ $item->product?->name ?? '—' }}</td>
                            <td class="text-center text-muted">{{ $item->quantity }}</td>
                            <td class="text-end text-muted">{{ number_format($item->unit_price, 2) }} €</td>
                            <td class="text-end fw-semibold">{{ number_format($item->unit_price * $item->quantity, 2) }} €</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end fw-bold py-3">Total</td>
                            <td class="text-end fw-bold py-3" style="color:var(--brand-primary);">
                                {{ number_format($order->total, 2) }} €
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module">
    window.Echo.private('orders.{{ $order->id }}')
        .listen('OrderStatusChanged', (e) => {
            const badge = document.getElementById('order-status-badge');
            badge.textContent = e.status_label;
            badge.className = 'badge status-large bg-' + e.badge_class;
        });
</script>
@endpush

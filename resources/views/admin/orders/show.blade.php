@extends('layouts.admin')

@section('title', 'Encomenda #' . $order->id)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Encomenda #{{ $order->id }}</h4>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">← Voltar</a>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm mb-3">
            <div class="card-header">Informação</div>
            <div class="card-body">
                <dl class="mb-0">
                    <dt>Cliente</dt>
                    <dd>
                        <a href="{{ route('admin.customers.show', $order->customer) }}">
                            {{ $order->customer?->company_name }}
                        </a>
                    </dd>
                    <dt>Estado</dt>
                    <dd><span class="badge bg-{{ $order->status->badgeClass() }}">{{ $order->status->label() }}</span></dd>
                    <dt>Total</dt>
                    <dd>{{ number_format($order->total, 2) }} €</dd>
                    <dt>Data</dt>
                    <dd>{{ $order->created_at->format('d/m/Y H:i') }}</dd>
                    @if ($order->notes)
                    <dt>Notas</dt>
                    <dd>{{ $order->notes }}</dd>
                    @endif
                </dl>
            </div>
        </div>

        <div class="card shadow-sm mb-3">
            <div class="card-header">Morada de Entrega</div>
            <div class="card-body">
                @if ($order->address)
                <address class="mb-0">
                    <strong>{{ $order->address->recipient_name }}</strong><br>
                    {{ $order->address->address_line }}<br>
                    {{ $order->address->postal_code }} {{ $order->address->city }}<br>
                    {{ $order->address->country }}
                    @if ($order->address->nif)
                    <br>NIF: {{ $order->address->nif }}
                    @endif
                </address>
                @endif
            </div>
        </div>

        @if (count($statuses) > 0)
        <div class="card shadow-sm">
            <div class="card-header">Alterar Estado</div>
            <div class="card-body">
                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                            @foreach ($statuses as $status)
                            <option value="{{ $status->value }}">{{ $status->label() }}</option>
                            @endforeach
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100">Atualizar Estado</button>
                </form>
            </div>
        </div>
        @else
        <div class="alert alert-secondary">Esta encomenda não tem transições de estado disponíveis.</div>
        @endif
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header">Itens da Encomenda</div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Produto</th>
                            <th>SKU</th>
                            <th class="text-center">Qtd</th>
                            <th class="text-end">Preço Unit.</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                        <tr>
                            <td>{{ $item->product?->name ?? '—' }}</td>
                            <td><code>{{ $item->product?->sku ?? '—' }}</code></td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end">{{ number_format($item->unit_price, 2) }} €</td>
                            <td class="text-end">{{ number_format($item->unit_price * $item->quantity, 2) }} €</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4" class="text-end fw-bold">Total</td>
                            <td class="text-end fw-bold">{{ number_format($order->total, 2) }} €</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

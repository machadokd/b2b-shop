@extends('layouts.admin')

@section('title', 'Encomendas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Encomendas</h4>
</div>

<form method="GET" class="card shadow-sm mb-3">
    <div class="card-body d-flex gap-3 flex-wrap align-items-end py-3">
        <div>
            <label class="form-label small mb-1">Estado</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">Todos</option>
                @foreach ($statuses as $status)
                <option value="{{ $status->value }}" {{ request('status') === $status->value ? 'selected' : '' }}>
                    {{ $status->label() }}
                </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label small mb-1">Cliente</label>
            <select name="customer_id" class="form-select form-select-sm">
                <option value="">Todos</option>
                @foreach ($customers as $customer)
                <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                    {{ $customer->company_name }}
                </option>
                @endforeach
            </select>
        </div>
        <button class="btn btn-outline-secondary btn-sm">Filtrar</button>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-link btn-sm">Limpar</a>
    </div>
</form>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Cliente</th>
                    <th>Estado</th>
                    <th>Total</th>
                    <th>Data</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->customer?->company_name }}</td>
                    <td>
                        <span class="badge bg-{{ $order->status->badgeClass() }}">
                            {{ $order->status->label() }}
                        </span>
                    </td>
                    <td>{{ number_format($order->total, 2) }} €</td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-primary btn-sm">Ver</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Nenhuma encomenda encontrada.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $orders->links() }}</div>
@endsection

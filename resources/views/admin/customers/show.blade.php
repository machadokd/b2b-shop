@extends('layouts.admin')

@section('title', 'Cliente — ' . $customer->company_name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">{{ $customer->company_name }}</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-outline-primary btn-sm">Editar</a>
        <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary btn-sm">← Voltar</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header">Dados do Cliente</div>
            <div class="card-body">
                <dl class="mb-0">
                    <dt>Nome</dt><dd>{{ $customer->user?->name }}</dd>
                    <dt>Email</dt><dd>{{ $customer->user?->email }}</dd>
                    <dt>NIF</dt><dd>{{ $customer->nif }}</dd>
                    <dt>Telefone</dt><dd>{{ $customer->phone }}</dd>
                    <dt>Estado</dt>
                    <dd>
                        <span class="badge bg-{{ $customer->is_blocked ? 'danger' : 'success' }}">
                            {{ $customer->is_blocked ? 'Bloqueado' : 'Ativo' }}
                        </span>
                    </dd>
                </dl>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Moradas</span>
                <a href="{{ route('admin.customers.addresses.create', $customer) }}" class="btn btn-sm btn-outline-primary">+ Adicionar</a>
            </div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Destinatário</th>
                            <th>Endereço</th>
                            <th>Cidade</th>
                            <th>Predefinida</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customer->addresses as $address)
                        <tr>
                            <td>{{ $address->recipient_name }}</td>
                            <td>{{ $address->address_line }}</td>
                            <td>{{ $address->city }}</td>
                            <td>{{ $address->is_default ? '✓' : '' }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.customers.addresses.edit', [$customer, $address]) }}" class="btn btn-outline-primary btn-sm">Editar</a>
                                <button class="btn btn-outline-danger btn-sm"
                                    data-confirm="Eliminar esta morada?"
                                    data-action="{{ route('admin.customers.addresses.destroy', [$customer, $address]) }}">Eliminar</button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">Sem moradas registadas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header">Encomendas recentes</div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr><th>#</th><th>Estado</th><th>Total</th><th>Data</th></tr>
                    </thead>
                    <tbody>
                        @forelse ($customer->orders->take(10) as $order)
                        <tr>
                            <td><a href="{{ route('admin.orders.show', $order) }}">#{{ $order->id }}</a></td>
                            <td><span class="badge bg-{{ $order->status->badgeClass() }}">{{ $order->status->label() }}</span></td>
                            <td>{{ number_format($order->total, 2) }} €</td>
                            <td>{{ $order->created_at->format('d/m/Y') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">Sem encomendas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

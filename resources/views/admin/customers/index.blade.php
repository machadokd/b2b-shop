@extends('layouts.admin')

@section('title', 'Clientes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Clientes</h4>
    <a href="{{ route('admin.customers.create') }}" class="btn btn-primary btn-sm">+ Novo Cliente</a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Empresa</th>
                    <th>Nome / Email</th>
                    <th>NIF</th>
                    <th>Telefone</th>
                    <th>Estado</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($customers as $customer)
                <tr>
                    <td>{{ $customer->company_name }}</td>
                    <td>
                        {{ $customer->user?->name }}<br>
                        <small class="text-muted">{{ $customer->user?->email }}</small>
                    </td>
                    <td>{{ $customer->nif }}</td>
                    <td>{{ $customer->phone }}</td>
                    <td>
                        <span class="badge bg-{{ $customer->is_blocked ? 'danger' : 'success' }}">
                            {{ $customer->is_blocked ? 'Bloqueado' : 'Ativo' }}
                        </span>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-outline-secondary btn-sm">Ver</a>
                        <form action="{{ route('admin.customers.toggle-blocked', $customer) }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-sm {{ $customer->is_blocked ? 'btn-outline-success' : 'btn-outline-warning' }}">
                                {{ $customer->is_blocked ? 'Desbloquear' : 'Bloquear' }}
                            </button>
                        </form>
                        <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-outline-primary btn-sm">Editar</a>
                        <button class="btn btn-outline-danger btn-sm"
                            data-confirm="Eliminar o cliente '{{ $customer->company_name }}'?"
                            data-action="{{ route('admin.customers.destroy', $customer) }}">Eliminar</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Nenhum cliente encontrado.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $customers->links() }}</div>
@endsection

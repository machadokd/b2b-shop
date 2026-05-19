@extends('layouts.admin')

@section('title', 'Produtos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Produtos</h4>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">+ Novo Produto</a>
</div>

<form method="GET" class="card shadow-sm mb-3">
    <div class="card-body d-flex gap-3 flex-wrap align-items-end py-3">
        <div>
            <label class="form-label small mb-1">Categoria</label>
            <select name="category_id" class="form-select form-select-sm">
                <option value="">Todas</option>
                @foreach ($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label small mb-1">Estado</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">Todos</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Ativo</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inativo</option>
            </select>
        </div>
        <button class="btn btn-outline-secondary btn-sm">Filtrar</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-link btn-sm">Limpar</a>
    </div>
</form>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nome</th>
                    <th>SKU</th>
                    <th>Categoria</th>
                    <th>Preço</th>
                    <th>Stock</th>
                    <th>Estado</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td><code>{{ $product->sku }}</code></td>
                    <td>{{ $product->category?->name ?? '—' }}</td>
                    <td>{{ number_format($product->price, 2) }} €</td>
                    <td>
                        <span class="{{ $product->stock === 0 ? 'text-danger fw-bold' : '' }}">
                            {{ $product->stock }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-{{ $product->is_active ? 'success' : 'secondary' }}">
                            {{ $product->is_active ? 'Ativo' : 'Inativo' }}
                        </span>
                    </td>
                    <td class="text-end">
                        <form action="{{ route('admin.products.toggle', $product) }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-outline-secondary btn-sm">
                                {{ $product->is_active ? 'Desativar' : 'Ativar' }}
                            </button>
                        </form>
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-outline-primary btn-sm">Editar</a>
                        <button class="btn btn-outline-danger btn-sm"
                            data-confirm="Eliminar o produto '{{ $product->name }}'?"
                            data-action="{{ route('admin.products.destroy', $product) }}">Eliminar</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Nenhum produto encontrado.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $products->links() }}</div>
@endsection

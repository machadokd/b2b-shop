@extends('layouts.admin')

@section('title', 'Catálogos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Catálogos</h4>
    <a href="{{ route('admin.catalogs.create') }}" class="btn btn-primary btn-sm">+ Novo Catálogo</a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nome</th>
                    <th>Slug</th>
                    <th>Produtos</th>
                    <th>Estado</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($catalogs as $catalog)
                <tr>
                    <td>{{ $catalog->name }}</td>
                    <td><code>{{ $catalog->slug }}</code></td>
                    <td>{{ $catalog->products_count }}</td>
                    <td>
                        <span class="badge bg-{{ $catalog->is_active ? 'success' : 'secondary' }}">
                            {{ $catalog->is_active ? 'Ativo' : 'Inativo' }}
                        </span>
                    </td>
                    <td class="text-end">
                        <form action="{{ route('admin.catalogs.toggle', $catalog) }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-outline-secondary btn-sm">
                                {{ $catalog->is_active ? 'Desativar' : 'Ativar' }}
                            </button>
                        </form>
                        <a href="{{ route('admin.catalogs.edit', $catalog) }}" class="btn btn-outline-primary btn-sm">Editar</a>
                        <button class="btn btn-outline-danger btn-sm"
                            data-confirm="Eliminar o catálogo '{{ $catalog->name }}'?"
                            data-action="{{ route('admin.catalogs.destroy', $catalog) }}">Eliminar</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-4">Nenhum catálogo encontrado.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $catalogs->links() }}</div>
@endsection

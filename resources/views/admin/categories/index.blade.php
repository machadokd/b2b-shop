@extends('layouts.admin')

@section('title', 'Categorias')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Categorias</h4>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">+ Nova Categoria</a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nome</th>
                    <th>Categoria Pai</th>
                    <th>Slug</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                <tr>
                    <td>
                        @if ($category->parent_id)
                            <span class="ms-3 text-muted">↳</span>
                        @endif
                        {{ $category->name }}
                    </td>
                    <td>{{ $category->parent?->name ?? '—' }}</td>
                    <td><code>{{ $category->slug }}</code></td>
                    <td class="text-end">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-outline-primary btn-sm">Editar</a>
                        <button class="btn btn-outline-danger btn-sm"
                            data-confirm="Eliminar a categoria '{{ $category->name }}'?"
                            data-action="{{ route('admin.categories.destroy', $category) }}">Eliminar</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-muted py-4">Nenhuma categoria encontrada.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $categories->links() }}</div>
@endsection

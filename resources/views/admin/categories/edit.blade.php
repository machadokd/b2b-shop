@extends('layouts.admin')

@section('title', 'Editar Categoria')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Editar Categoria</h4>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary btn-sm">← Voltar</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')
            @include('admin.categories._form', ['category' => $category])
            <button type="submit" class="btn btn-primary">Guardar Alterações</button>
        </form>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Editar Catálogo')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Editar Catálogo</h4>
    <a href="{{ route('admin.catalogs.index') }}" class="btn btn-outline-secondary btn-sm">← Voltar</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.catalogs.update', $catalog) }}" method="POST">
            @csrf
            @method('PUT')
            @include('admin.catalogs._form', ['catalog' => $catalog])
            <button type="submit" class="btn btn-primary">Guardar Alterações</button>
        </form>
    </div>
</div>
@endsection

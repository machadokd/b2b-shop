@extends('layouts.admin')

@section('title', 'Editar Produto')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Editar Produto</h4>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">← Voltar</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.products._form', ['product' => $product])
            <button type="submit" class="btn btn-primary">Guardar Alterações</button>
        </form>
    </div>
</div>
@endsection

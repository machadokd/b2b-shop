@extends('layouts.admin')

@section('title', 'Novo Produto')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Novo Produto</h4>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">← Voltar</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.products._form', ['product' => null])
            <button type="submit" class="btn btn-primary">Criar Produto</button>
        </form>
    </div>
</div>
@endsection

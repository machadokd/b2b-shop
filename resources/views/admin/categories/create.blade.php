@extends('layouts.admin')

@section('title', 'Nova Categoria')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Nova Categoria</h4>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary btn-sm">← Voltar</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            @include('admin.categories._form', ['category' => null])
            <button type="submit" class="btn btn-primary">Criar Categoria</button>
        </form>
    </div>
</div>
@endsection

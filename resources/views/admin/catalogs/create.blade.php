@extends('layouts.admin')

@section('title', 'Novo Catálogo')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Novo Catálogo</h4>
    <a href="{{ route('admin.catalogs.index') }}" class="btn btn-outline-secondary btn-sm">← Voltar</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.catalogs.store') }}" method="POST">
            @csrf
            @include('admin.catalogs._form', ['catalog' => null])
            <button type="submit" class="btn btn-primary">Criar Catálogo</button>
        </form>
    </div>
</div>
@endsection

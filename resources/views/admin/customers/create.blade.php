@extends('layouts.admin')

@section('title', 'Novo Cliente')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Novo Cliente</h4>
    <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary btn-sm">← Voltar</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.customers.store') }}" method="POST">
            @csrf
            @include('admin.customers._form', ['customer' => null])
            <button type="submit" class="btn btn-primary">Criar Cliente</button>
        </form>
    </div>
</div>
@endsection

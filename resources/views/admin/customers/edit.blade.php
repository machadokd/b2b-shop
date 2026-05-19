@extends('layouts.admin')

@section('title', 'Editar Cliente')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Editar Cliente — {{ $customer->company_name }}</h4>
    <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary btn-sm">← Voltar</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.customers.update', $customer) }}" method="POST">
            @csrf
            @method('PUT')
            @include('admin.customers._form', ['customer' => $customer])
            <button type="submit" class="btn btn-primary">Guardar Alterações</button>
        </form>
    </div>
</div>
@endsection

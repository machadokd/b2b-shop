@extends('layouts.admin')

@section('title', 'Editar Morada')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Editar Morada — {{ $customer->company_name }}</h4>
    <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-outline-secondary btn-sm">← Voltar</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.customers.addresses.update', [$customer, $address]) }}" method="POST">
            @csrf
            @method('PUT')
            @include('admin.addresses._form', ['address' => $address])
            <button type="submit" class="btn btn-primary">Guardar Alterações</button>
        </form>
    </div>
</div>
@endsection

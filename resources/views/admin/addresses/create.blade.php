@extends('layouts.admin')

@section('title', 'Nova Morada')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Nova Morada — {{ $customer->company_name }}</h4>
    <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-outline-secondary btn-sm">← Voltar</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.customers.addresses.store', $customer) }}" method="POST">
            @csrf
            @include('admin.addresses._form', ['address' => null])
            <button type="submit" class="btn btn-primary">Adicionar Morada</button>
        </form>
    </div>
</div>
@endsection

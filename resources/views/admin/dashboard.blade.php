@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<h4 class="mb-4">Dashboard</h4>

<div class="row g-3">
    <div class="col-md-3">
        <div class="card shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-primary">{{ $totalProducts }}</div>
            <div class="text-muted small">Produtos ativos</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-success">{{ $totalCustomers }}</div>
            <div class="text-muted small">Clientes</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-warning">{{ $pendingOrders }}</div>
            <div class="text-muted small">Encomendas pendentes</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-info">{{ $totalOrders }}</div>
            <div class="text-muted small">Total encomendas</div>
        </div>
    </div>
</div>
@endsection

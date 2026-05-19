<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — B2B Shop</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<div class="d-flex min-vh-100">
    <nav class="d-flex flex-column p-3 bg-dark text-white flex-shrink-0" style="width:240px">
        <a class="text-white text-decoration-none fw-bold fs-5 mb-4 d-block" href="{{ route('admin.dashboard') }}">
            B2B Shop Admin
        </a>
        <ul class="nav nav-pills flex-column gap-1 mb-auto">
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                   href="{{ route('admin.dashboard') }}">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('admin.catalogs.*') ? 'active' : '' }}"
                   href="{{ route('admin.catalogs.index') }}">Catálogos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"
                   href="{{ route('admin.categories.index') }}">Categorias</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('admin.products.*') ? 'active' : '' }}"
                   href="{{ route('admin.products.index') }}">Produtos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}"
                   href="{{ route('admin.customers.index') }}">Clientes</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"
                   href="{{ route('admin.orders.index') }}">Encomendas</a>
            </li>
        </ul>
        <form method="POST" action="{{ route('admin.logout') }}" class="mt-3">
            @csrf
            <button class="btn btn-outline-light btn-sm w-100">Logout</button>
        </form>
    </nav>

    <div class="flex-grow-1 d-flex flex-column overflow-auto">
        <nav class="navbar bg-white border-bottom px-4 flex-shrink-0">
            <span class="text-muted small">{{ Auth::user()->name }}</span>
        </nav>

        <main class="p-4 flex-grow-1 bg-light">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

@include('components.confirm-modal')
@stack('scripts')
</body>
</html>

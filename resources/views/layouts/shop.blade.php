<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Loja') — B2B Shop</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

<nav class="navbar navbar-shop navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('shop.products.index') }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
                <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
            </svg>
            B2B Shop
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#shopNav"
                style="filter: brightness(0) invert(1);">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="shopNav">
            <ul class="navbar-nav me-auto gap-1 mt-2 mt-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('shop.products.*') ? 'active' : '' }}"
                       href="{{ route('shop.products.index') }}">Produtos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('shop.orders.*') ? 'active' : '' }}"
                       href="{{ route('shop.orders.index') }}">Encomendas</a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto align-items-lg-center gap-2 mt-2 mt-lg-0">
                <li class="nav-item">
                    @php $cartCount = collect(session('cart', []))->sum('quantity') @endphp
                    <a class="nav-link cart-link {{ request()->routeIs('shop.cart.*') ? 'active' : '' }}"
                       href="{{ route('shop.cart.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                        </svg>
                        Carrinho
                        @if ($cartCount > 0)
                            <span class="cart-badge">{{ $cartCount }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item d-none d-lg-block">
                    <span class="navbar-user-name">{{ Auth::user()->name }}</span>
                </li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('shop.logout') }}">
                        @csrf
                        <button class="btn btn-sm btn-outline-light" style="font-size:.8rem;padding:.25rem .75rem;">Sair</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="container py-4 py-md-5">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
            </svg>
            {{ session('success') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</main>

<footer class="shop-footer">
    &copy; {{ date('Y') }} B2B Shop — Plataforma de Comércio B2B
</footer>

@stack('scripts')
</body>
</html>

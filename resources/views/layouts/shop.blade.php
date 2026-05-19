<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Loja') — B2B Shop</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('shop.products.index') }}">B2B Shop</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#shopNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="shopNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('shop.products.*') ? 'active' : '' }}"
                       href="{{ route('shop.products.index') }}">Produtos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('shop.orders.*') ? 'active' : '' }}"
                       href="{{ route('shop.orders.index') }}">Encomendas</a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto align-items-center gap-2">
                <li class="nav-item">
                    <a class="nav-link position-relative {{ request()->routeIs('shop.cart.*') ? 'active' : '' }}"
                       href="{{ route('shop.cart.index') }}">
                        Carrinho
                        @php $cartCount = collect(session('cart', []))->sum('quantity') @endphp
                        @if ($cartCount > 0)
                        <span class="badge bg-danger position-absolute top-0 start-100 translate-middle rounded-pill">
                            {{ $cartCount }}
                        </span>
                        @endif
                    </a>
                </li>
                <li class="nav-item text-white opacity-75 small">
                    {{ Auth::user()->name }}
                </li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('shop.logout') }}">
                        @csrf
                        <button class="btn btn-outline-light btn-sm">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="container py-4">
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
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</main>

<footer class="bg-dark text-white text-center py-3 mt-5 small">
    &copy; {{ date('Y') }} B2B Shop
</footer>

@stack('scripts')
</body>
</html>

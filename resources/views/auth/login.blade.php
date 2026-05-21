@extends('layouts.auth')

@section('title', 'Entrar')

@section('content')
    <h2 class="fw-700 mb-1" style="font-size:1.25rem;font-weight:700;">Bem-vindo de volta</h2>
    <p class="text-muted mb-4" style="font-size:.875rem;">Inicia sessão para aceder à loja</p>

    @if ($errors->any())
        <div class="alert alert-danger py-2 mb-3" style="font-size:.875rem;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('shop.login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label fw-medium" style="font-size:.875rem;">Email</label>
            <input type="email" id="email" name="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" autofocus required
                   placeholder="nome@empresa.pt">
        </div>

        <div class="mb-3">
            <label for="password" class="form-label fw-medium" style="font-size:.875rem;">Password</label>
            <input type="password" id="password" name="password" class="form-control" required
                   placeholder="••••••••">
        </div>

        <div class="mb-4 form-check">
            <input type="checkbox" class="form-check-input" id="remember" name="remember">
            <label class="form-check-label" for="remember" style="font-size:.875rem;">Lembrar-me</label>
        </div>

        <button type="submit" class="btn btn-primary w-100 fw-semibold" style="padding:.65rem;">
            Entrar
        </button>
    </form>
@endsection

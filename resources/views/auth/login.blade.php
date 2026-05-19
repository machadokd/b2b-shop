@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <h4 class="mb-4 text-center fw-semibold">B2B Shop</h4>

    @if ($errors->any())
        <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('shop.login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" autofocus required>
        </div>

        <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="remember" name="remember">
            <label class="form-check-label" for="remember">Lembrar-me</label>
        </div>

        <button type="submit" class="btn btn-primary w-100">Entrar</button>
    </form>
@endsection

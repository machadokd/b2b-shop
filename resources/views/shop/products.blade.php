@extends('layouts.auth')

@section('title', 'Produtos')

@section('content')
    <h4>Produtos — em construção</h4>
    <form method="POST" action="{{ route('shop.logout') }}">
        @csrf
        <button class="btn btn-sm btn-outline-secondary mt-3">Logout</button>
    </form>
@endsection

@extends('layouts.auth')

@section('title', 'Dashboard')

@section('content')
    <h4>Dashboard — em construção</h4>
    <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button class="btn btn-sm btn-outline-secondary mt-3">Logout</button>
    </form>
@endsection

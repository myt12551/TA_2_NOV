@extends('layouts.app')

@section('title', 'Dashboard Customer')

@section('content')
<div class="container py-4">
    <div class="alert alert-success">Selamat datang di Dashboard Customer.</div>
    <a href="{{ route('marketplace.index') }}" class="btn btn-primary">Belanja Sekarang</a>
</div>
@endsection

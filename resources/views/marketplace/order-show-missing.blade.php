@extends('layouts.app')
@section('use_bootstrap', true)

@section('title', 'Nota Pesanan')
@section('content')
<div class="container py-4">
  <div class="alert alert-warning">
    Data nota untuk kode <strong>{{ $code }}</strong> tidak ditemukan. Silakan cek ulang atau buat pesanan lagi.
  </div>
  <a href="{{ route('marketplace.index') }}" class="btn btn-primary">Kembali ke Marketplace</a>
</div>
@endsection

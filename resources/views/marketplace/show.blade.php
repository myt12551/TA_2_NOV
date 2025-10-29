@extends('layouts.app')
@section('use_bootstrap', true)

@section('title', 'Marketplace')

@section('content')
<div class="container py-4">
  <div class="row g-4">
    <div class="col-md-5">
      <div class="border rounded p-2">
        <img src="{{ $item->photo_url ?? asset('images/no-image.png') }}"
             alt="{{ $item->name }}"
             class="img-fluid w-100"
             style="object-fit:cover;">
      </div>
    </div>

    <div class="col-md-7">
      <h4 class="mb-1">{{ $item->name }}</h4>
      <div class="text-muted mb-2">Kode: {{ $item->code }}</div>

      <div class="h4 fw-bold text-primary mb-3">
        {{ $item->selling_price_formatted ?? ('Rp ' . number_format((float)$item->selling_price, 0, ',', '.')) }}
      </div>

      @if(!empty($item->description))
        <p class="mb-3">{{ $item->description }}</p>
      @endif

      <div class="mb-3">
        <span class="badge bg-{{ (int)$item->stock > 0 ? 'success' : 'secondary' }}">
          Stok: {{ (int)$item->stock }}
        </span>
      </div>

      <form action="{{ route('marketplace.cart.add') }}" method="POST" class="d-flex align-items-center" style="gap:.5rem;">
        @csrf
        <input type="hidden" name="item_id" value="{{ $item->id }}">
        <input type="number"
               name="qty"
               class="form-control"
               value="1"
               min="1"
               max="{{ max(1, (int)$item->stock) }}"
               style="max-width:120px;"
               @if((int)$item->stock <= 0) disabled @endif>
        <button class="btn btn-primary" @if((int)$item->stock <= 0) disabled @endif>
          Tambah ke Keranjang
        </button>
      </form>

      <div class="mt-3">
        <a href="{{ route('marketplace.index') }}" class="btn btn-outline-secondary btn-sm">
          ← Kembali ke Etalase
        </a>
      </div>
    </div>
  </div>
</div>
@endsection

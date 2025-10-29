@extends('layouts.app')
@section('use_bootstrap', true)

@section('title', 'Marketplace')
@section('content')
<style>
  /* Tema terang & sentuhan modern */
  .market-hero {
    background: linear-gradient(135deg, #f8fafc, #ffffff);
    border: 1px solid #eef2f7;
  }
  .btn-soft {
    background-color: #f1f5f9; border: 1px solid #e2e8f0;
  }
  .card-product img {
    transition: transform .18s ease;
  }
  .card-product:hover img {
    transform: scale(1.02);
  }
  .badge-soft {
    background: #eef6ff; color: #0d6efd; border: 1px solid #dbeafe;
  }
  .chip {
    border: 1px solid #e5e7eb; background: #ffffff; color: #111827;
    padding: .35rem .7rem; border-radius: 999px; font-size: .85rem;
  }
  .chip:hover { background: #f8fafc; }
</style>

<div class="container py-4">

  {{-- Topbar (brand + keranjang) --}}
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div class="d-flex align-items-center gap-2">
      <span class="fs-4 fw-semibold text-dark">Teaching Factory Marketplace</span>
      <span class="badge badge-soft rounded-pill">Live</span>
    </div>

    <div class="d-flex align-items-center gap-2">
      <a href="{{ route('marketplace.cart') }}" class="btn btn-outline-primary position-relative">
        Keranjang
        @if(($cartCount ?? 0) > 0)
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            {{ $cartCount }}
          </span>
        @endif
      </a>
    </div>
  </div>
  <div class="d-flex align-items-center gap-2">
  <a href="{{ route('marketplace.cart') }}" class="btn btn-outline-primary position-relative">
    Keranjang 
    @if(($cartCount ?? 0) > 0)
      <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
        {{ $cartCount }}
      </span>
    @endif
  </a>
  @auth  <!-- Hanya tampil jika user sudah login -->
    <a href="{{ route('marketplace.order.index') }}" class="btn btn-outline-secondary">
      Pesanan Saya
    </a>
  @endauth
</div>


  {{-- Hero sederhana --}}
  <div class="market-hero rounded-3 p-4 p-md-5 mb-4">
    <div class="row align-items-center g-4">
      <div class="col-lg-8">
        <h2 class="fw-bold mb-2">Belanja Produk Unggulan</h2>
        <p class="text-muted mb-3">
          Pilih produk, lihat detail, tambah ke keranjang, dan selesaikan pesanan untuk pickup di lokasi.
        </p>
        <div class="d-flex flex-wrap gap-2">
          <a href="#produk" class="btn btn-primary">Jelajahi Produk</a>
          <a href="{{ route('marketplace.cart') }}" class="btn btn-soft">Lihat Keranjang</a>
        </div>
      </div>
      <div class="col-lg-4 text-lg-end">
        <img src="{{ asset('images/hero-market.png') }}"
             alt="Marketplace Illustration"
             class="img-fluid"
             onerror="this.style.display='none'">
      </div>
    </div>
  </div>

  {{-- Teaser kategori / koleksi (opsional non-dependen, aman) --}}
  <div class="d-flex flex-wrap gap-2 mb-4">
    <span class="chip">Terbaru</span>
    <span class="chip">Terlaris</span>
    <span class="chip">Promo</span>
    <span class="chip">Semua Produk</span>
  </div>

  {{-- Grid Produk --}}
  <div id="produk"></div>
  @if($items->count() === 0)
    <div class="alert alert-info">Belum ada produk untuk ditampilkan.</div>
  @else
    <div class="row">
      @foreach($items as $item)
        <div class="col-6 col-md-4 col-lg-3 mb-4">
          @include('marketplace.partials.product-card', ['item' => $item])
        </div>
      @endforeach
    </div>

    {{-- Paginasi --}}
    <div class="d-flex justify-content-center">
      {{ $items->links() }}
    </div>
  @endif

</div>
@endsection

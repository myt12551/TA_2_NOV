@extends('layouts.app')
@section('use_bootstrap', true)

@section('title', 'Checkout')
@section('content')
<div class="container py-4">

  <h4 class="mb-3">Checkout</h4>

  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
  @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div> @endif
  @if ($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
  @endif

  <form action="{{ route('marketplace.checkout.store') }}" method="POST" class="card shadow-sm mb-3">
    @csrf
    <div class="card-body">

      <div class="row g-3 mb-3">
        <div class="col-md-4">
          <label class="form-label">Nama Pengambil (pickup_name)</label>
          <input type="text" name="pickup_name" class="form-control" required
                 value="{{ old('pickup_name', $buyerName ?? '') }}" placeholder="Nama pengambil">
        </div>
        <div class="col-md-4">
          <label class="form-label">Nomor HP (phone)</label>
          <input type="text" name="phone" class="form-control" required
                 value="{{ old('phone') }}" placeholder="08xxxxxxxxxx">
        </div>
        <div class="col-md-4">
          <label class="form-label">Catatan (notes)</label>
          <input type="text" name="notes" class="form-control"
                 value="{{ old('notes') }}" placeholder="Contoh: ambil jam 16.00">
        </div>
      </div>

      <div class="table-responsive mb-3">
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th style="width:60px;">#</th>
              <th>Produk</th>
              <th style="width:120px;">Qty</th>
              <th style="width:140px;">Harga</th>
              <th style="width:160px;">Subtotal</th>
            </tr>
          </thead>
          <tbody>
            @foreach($rows as $r)
              @php $it = $r['item']; @endphp
              <tr>
                <td>
                  <img src="{{ $it->photo_url ?? asset('images/no-image.png') }}"
                       class="img-thumbnail" style="width:56px;height:56px;object-fit:cover;">
                </td>
                <td>
                  <div class="fw-semibold">{{ $it->name }}</div>
                  <div class="text-muted small">Kode: {{ $it->code }}</div>
                </td>
                <td>{{ (int)$r['qty'] }}</td>
                <td>Rp {{ number_format($r['price'], 0, ',', '.') }}</td>
                <td class="fw-semibold">Rp {{ number_format($r['subtotal'], 0, ',', '.') }}</td>
              </tr>
            @endforeach
            <tr>
              <th colspan="4" class="text-end">Total</th>
              <th class="fw-bold">Rp {{ number_format($total, 0, ',', '.') }}</th>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="alert alert-info">
        Pembayaran dilakukan <strong>tunai di tempat (COD/Pickup)</strong> saat barang diambil.
      </div>

      <div class="d-flex justify-content-between">
        <a href="{{ route('marketplace.cart') }}" class="btn btn-outline-secondary">← Kembali ke Keranjang</a>
        <button class="btn btn-primary">Buat Pesanan</button>
      </div>

    </div>
  </form>

</div>
@endsection

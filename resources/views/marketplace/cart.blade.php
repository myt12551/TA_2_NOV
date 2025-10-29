@extends('layouts.app')
@section('use_bootstrap', true)

@section('title', 'Keranjang')
@section('content')
<div class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Keranjang</h4>
    <div class="d-flex gap-2">
      <a href="{{ route('marketplace.index') }}" class="btn btn-outline-secondary btn-sm">← Lanjut Belanja</a>
      @if(!empty($rows) && count($rows) > 0)
      <form action="{{ route('marketplace.cart.clear') }}" method="POST"
            onsubmit="return confirm('Kosongkan keranjang?')">
        @csrf
        <button class="btn btn-outline-danger btn-sm">Kosongkan</button>
      </form>
      @endif
    </div>
  </div>

  {{-- Flash & Errors --}}
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
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

  @php
    // $rows: array of ['item'=>Item, 'qty'=>int, 'price'=>float, 'subtotal'=>float]
    $hasRows = !empty($rows) && count($rows) > 0;
  @endphp

  @if(!$hasRows)
    <div class="alert alert-info">
      Keranjang Anda masih kosong.
      <a href="{{ route('marketplace.index') }}" class="alert-link">Belanja sekarang</a>.
    </div>
  @else
    <div class="table-responsive mb-3">
      <table class="table table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th style="width:60px;">#</th>
            <th>Produk</th>
            <th style="width:140px;">Qty</th>
            <th style="width:160px;">Harga</th>
            <th style="width:180px;">Subtotal</th>
            <th style="width:90px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($rows as $row)
            @php
              $item = $row['item'];
              $qty = (int) $row['qty'];
              $price = (float) $row['price'];
              $subtotal = (float) $row['subtotal'];
            @endphp
            <tr>
              <td>
                <img src="{{ $item->photo_url ?? asset('images/no-image.png') }}"
                     class="img-thumbnail" style="width:56px;height:56px;object-fit:cover;">
              </td>
              <td>
                <div class="fw-semibold">{{ $item->name }}</div>
                <div class="text-muted small">Kode: {{ $item->code }}</div>
                <div class="small mt-1">
                  Stok:
                  <span class="badge bg-{{ (int)$item->stock > 0 ? 'success' : 'secondary' }}">
                    {{ (int)$item->stock }}
                  </span>
                </div>
              </td>
              <td>
                <form action="{{ route('marketplace.cart.update') }}" method="POST" class="d-flex" style="gap:.5rem;">
                  @csrf
                  <input type="hidden" name="item_id" value="{{ $item->id }}">
                  <input type="number" name="qty" min="1"
                         max="{{ max(1, (int)$item->stock) }}"
                         value="{{ $qty }}" class="form-control">
                  <button class="btn btn-sm btn-outline-primary">Update</button>
                </form>
              </td>
              <td>Rp {{ number_format($price, 0, ',', '.') }}</td>
              <td class="fw-semibold">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
              <td>
                <form action="{{ route('marketplace.cart.remove') }}" method="POST"
                      onsubmit="return confirm('Hapus item ini?')">
                  @csrf
                  <input type="hidden" name="item_id" value="{{ $item->id }}">
                  <button class="btn btn-sm btn-outline-danger w-100">Hapus</button>
                </form>
              </td>
            </tr>
          @endforeach
          <tr>
            <th colspan="4" class="text-end">Total</th>
            <th colspan="2" class="fw-bold">Rp {{ number_format($total, 0, ',', '.') }}</th>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="d-flex justify-content-end">
      <a href="{{ route('marketplace.checkout') }}" class="btn btn-primary">
        Lanjut ke Checkout
      </a>
    </div>
  @endif

</div>
@endsection

@extends('layouts.app')
@section('use_bootstrap', true)

@section('title', 'Pesanan Saya')
@section('content')
<div class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Pesanan Saya</h4>
    <div>
      <a href="{{ route('marketplace.index') }}" class="btn btn-outline-secondary btn-sm">
        ← Kembali Belanja
      </a>
    </div>
  </div>

  {{-- Flash messages --}}
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  @if($orders->isEmpty())
    <div class="alert alert-info">
      Anda belum memiliki pesanan.
      <a href="{{ route('marketplace.index') }}" class="alert-link">Belanja sekarang</a>.
    </div>
  @else
    <div class="table-responsive mb-3">
      <table class="table table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th style="width:60px;">#</th>
            <th>Kode Pesanan</th>
            <th>Tanggal</th>
            <th>Status</th>
            <th>Total</th>
            <th style="width:90px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($orders as $index => $order)
            <tr>
              <td>{{ $index + 1 }}</td>
              <td>{{ $order->code }}</td>
              <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</td>
              <td>
                @if($order->status === 'pending_pickup')
                  <span class="badge bg-warning text-dark">Menunggu Diambil</span>
                @elseif($order->status === 'completed')
                  <span class="badge bg-success">Selesai</span>
                @else 
                  <span class="badge bg-secondary text-light">{{ $order->status }}</span>
                @endif
              </td>
              <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
              <td>
                <a href="{{ route('marketplace.order.show', ['code' => $order->code]) }}" 
                   class="btn btn-sm btn-outline-primary">
                  Lihat
                </a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif

</div>
@endsection

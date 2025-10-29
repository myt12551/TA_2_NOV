@extends('layouts.app')
@section('use_bootstrap', true)

@section('title', 'Nota Pesanan')
@section('content')
<div class="container py-4">

  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Nota Pesanan</h4>
    <div class="d-flex gap-2">
      <a href="{{ route('marketplace.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
      <button id="btn-download" class="btn btn-primary btn-sm">Unduh PNG</button>
      <button onclick="window.print()" class="btn btn-outline-primary btn-sm">Cetak</button>
    </div>
  </div>

  <div id="nota" class="card shadow-sm">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
        <div>
          <div class="fw-bold fs-5">Teaching Factory Marketplace</div>
          <div class="text-muted small">Pembayaran: <strong>COD/Pickup (Tunai di tempat)</strong></div>
          <div class="text-muted small">Pengambil: <strong>{{ $order->pickup_name }}</strong></div>
          <div class="text-muted small">HP: <strong>{{ $order->phone }}</strong></div>
          @if($order->notes)
            <div class="text-muted small">Catatan: <em>{{ $order->notes }}</em></div>
          @endif
        </div>
        <div class="text-end">
          <div class="fw-bold">Kode Pesanan</div>
          <div class="fs-5">{{ $order->code }}</div>
          <small class="text-muted">
            Tanggal: {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}
          </small>
        </div>
      </div>

      <hr>

      <div class="table-responsive mb-3">
        <table class="table table-sm table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Produk</th>
              <th style="width:80px;">Qty</th>
              <th style="width:140px;">Harga</th>
              <th style="width:160px;">Subtotal</th>
            </tr>
          </thead>
          <tbody>
            @foreach($rows as $i => $r)
              <tr>
                <td>{{ $i+1 }}</td>
                <td>
                  <div class="fw-semibold">{{ $r['name'] }}</div>
                  <div class="text-muted small">Kode: {{ $r['code'] }}</div>
                </td>
                <td>{{ $r['qty'] }}</td>
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

      <div class="alert alert-info mb-0">
        Tunjukkan nota ini saat pengambilan dan lakukan pembayaran <strong>tunai di tempat</strong>.
        Setelah dibayar, kasir akan menyelesaikan transaksi di sistem POS.
      </div>
    </div>
  </div>

</div>

{{-- html2canvas untuk unduh PNG --}}
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"
        integrity="sha256-1FfAC+zs7nH2yQQfGzF0uQjYj91h3HAG0OV9K8d3G3A=" crossorigin="anonymous"></script>
<script>
document.getElementById('btn-download')?.addEventListener('click', function () {
  const el = document.getElementById('nota');
  if (!el) return;
  html2canvas(el, {backgroundColor: '#ffffff', scale: 2}).then(canvas => {
    const a = document.createElement('a');
    a.download = 'nota-{{ $order->code }}.png';
    a.href = canvas.toDataURL('image/png');
    a.click();
  });
});
</script>

<style>
@media print { #btn-download, .btn-outline-secondary, .btn-outline-primary { display:none!important } }
</style>
@endsection

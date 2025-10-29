@extends('layouts.app')

{{-- Halaman detail PO menggunakan Bootstrap --}}
@section('use_bootstrap', true)

@section('title', 'Detail Purchase Order')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5>Detail Purchase Order</h5>
        <a href="{{ route('procurement.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>No PO:</strong> {{ $po->po_number }}
                </div>
                <div class="col-md-4">
                    <strong>Tanggal:</strong> {{ $po->po_date }}
                </div>
                <div class="col-md-4">
                    <strong>Status:</strong> <span class="badge bg-secondary">{{ ucfirst($po->status) }}</span>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Supplier:</strong> {{ $po->supplier->name ?? '-' }}
                </div>
                <div class="col-md-6">
                    <strong>Total:</strong> Rp {{ number_format($po->total_amount, 0, ',', '.') }}
                </div>
            </div>
            <h6>Daftar Item</h6>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Harga Satuan</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($po->items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($item->quantity * $item->unit_price, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                <!-- Supplier validation -->
                @if ($po->status === 'draft')
                    <form action="{{ route('purchase-orders.validate', $po->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-warning">Konfirmasi Supplier</button>
                    </form>
                @endif
                <!-- Goods receipt stage -->
                @if ($po->status === 'validated' && !$po->goodsReceipts->count())
                    <a href="{{ route('goods-receipts.create', $po->id) }}" class="btn btn-secondary mt-2">Buat GR</a>
                @elseif ($po->goodsReceipts->count())
                    <a href="{{ route('goods-receipts.show', $po->goodsReceipts->first()->id) }}" class="btn btn-info mt-2">Lihat GR</a>
                @endif
                <!-- Invoice stage -->
                @php
                    $invoice = $po->invoices->first();
                @endphp
                @if ($po->status === 'received' && !$invoice)
                    <a href="{{ route('invoices.create', $po->id) }}" class="btn btn-secondary mt-2">Unggah Invoice</a>
                @elseif ($invoice && $invoice->status === 'unpaid')
                    <div class="d-flex gap-2 mt-2">
                        <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-info">Lihat Invoice</a>
                        <form action="{{ route('invoices.mark-paid', $invoice->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success">Tandai Dibayar</button>
                        </form>
                    </div>
                @elseif ($invoice && $invoice->status === 'paid')
                    <span class="badge bg-success mt-2">Invoice Lunas</span>
                @endif
            </div>
        </div>
    </div>
@endsection
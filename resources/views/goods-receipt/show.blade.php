@extends('layouts.app')

{{-- Halaman detail GR menggunakan Bootstrap --}}
@section('use_bootstrap', true)

@section('title', 'Detail Goods Receipt')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5>Detail Goods Receipt</h5>
        <a href="{{ route('goods-receipts.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4"><strong>No GR:</strong> {{ $receipt->gr_number }}</div>
                <div class="col-md-4"><strong>Tanggal:</strong> {{ $receipt->receipt_date }}</div>
                <div class="col-md-4"><strong>Status:</strong> <span class="badge bg-secondary">{{ ucfirst($receipt->status) }}</span></div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6"><strong>No PO:</strong> {{ $receipt->purchaseOrder->po_number ?? '-' }}</div>
                <div class="col-md-6"><strong>Dicatat oleh:</strong> {{ $receipt->receiver->name ?? '-' }}</div>
            </div>
            <div class="mb-3">
                <strong>Catatan:</strong>
                <p>{{ $receipt->notes ?? '-' }}</p>
            </div>
            <h6>Item yang Diterima</h6>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th>Jumlah Diterima</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($receipt->items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->quantity_received }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
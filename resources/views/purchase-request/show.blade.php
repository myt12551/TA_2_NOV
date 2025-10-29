@extends('layouts.app')

{{-- Halaman detail PR menggunakan Bootstrap --}}
@section('use_bootstrap', true)

@section('title', 'Detail Permintaan Pembelian')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5>Detail Permintaan Pembelian</h5>
        <a href="{{ route('purchase-requests.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>No PR:</strong> {{ $pr->pr_number }}
                </div>
                <div class="col-md-6">
                    <strong>Tanggal:</strong> {{ $pr->request_date }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Status:</strong> <span class="badge bg-secondary">{{ ucfirst($pr->status) }}</span>
                </div>
                <div class="col-md-6">
                    <strong>Diminta oleh:</strong> {{ $pr->requester->name ?? '-' }}
                </div>
            </div>
            <div class="mb-3">
                <strong>Keterangan:</strong>
                <p>{{ $pr->description ?? '-' }}</p>
            </div>
            <h6>Item</h6>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Satuan</th>
                        <th>Stok Sekarang</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pr->items as $item)
                        @php
                            $itemData = \App\Models\Item::where('name', $item->product_name)->first();
                        @endphp
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->unit ?? '-' }}</td>
                            <td>{{ $itemData ? $itemData->stock : '-' }}</td>
                            <td>{{ $item->notes }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if ($pr->status === 'pending')
                <div class="d-flex gap-2 mt-3">
                    <form action="{{ route('purchase-requests.approve', $pr->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">Setujui</button>
                    </form>
                    <form action="{{ route('purchase-requests.reject', $pr->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">Tolak</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection
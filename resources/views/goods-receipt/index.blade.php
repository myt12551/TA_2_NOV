@extends('layouts.app')

{{-- Halaman daftar GR menggunakan Bootstrap --}}
@section('use_bootstrap', true)

@section('title', 'Daftar Penerimaan Barang')

@section('content')
    <h5 class="mb-3">Daftar Penerimaan Barang (GR)</h5>
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>No GR</th>
                        <th>Tanggal</th>
                        <th>No PO</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($receipts as $gr)
                        <tr>
                            <td>{{ $gr->gr_number }}</td>
                            <td>{{ $gr->receipt_date }}</td>
                            <td>{{ $gr->purchaseOrder->po_number ?? '-' }}</td>
                            <td><span class="badge bg-secondary">{{ ucfirst($gr->status) }}</span></td>
                            <td>
                                <a href="{{ route('goods-receipts.show', $gr->id) }}" class="btn btn-sm btn-info">Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
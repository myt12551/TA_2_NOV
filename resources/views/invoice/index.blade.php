@extends('layouts.app')

{{-- Halaman daftar invoice menggunakan Bootstrap --}}
@section('use_bootstrap', true)

@section('title', 'Daftar Invoice')

@section('content')
    <h5 class="mb-3">Daftar Invoice</h5>
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>No Invoice</th>
                        <th>Tanggal</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>No PO</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $inv)
                        <tr>
                            <td>{{ $inv->invoice_number }}</td>
                            <td>{{ $inv->invoice_date }}</td>
                            <td>Rp {{ number_format($inv->amount, 0, ',', '.') }}</td>
                            <td><span class="badge {{ $inv->status === 'paid' ? 'bg-success' : 'bg-warning' }}">{{ ucfirst($inv->status) }}</span></td>
                            <td>{{ $inv->purchaseOrder->po_number ?? '-' }}</td>
                            <td>
                                <a href="{{ route('invoices.show', $inv->id) }}" class="btn btn-sm btn-info">Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
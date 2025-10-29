@extends('layouts.app')

{{-- Halaman ini menggunakan Bootstrap --}}
@section('use_bootstrap', true)

@section('title', 'Daftar Permintaan Pembelian')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5>Daftar Permintaan Pembelian</h5>
        <a href="{{ route('purchase-requests.create') }}" class="btn btn-primary btn-sm">Tambah PR</a>
    </div>
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>No PR</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $pr)
                        <tr>
                            <td>{{ $pr->pr_number }}</td>
                            <td>{{ $pr->request_date }}</td>
                            <td><span class="badge bg-secondary">{{ ucfirst($pr->status) }}</span></td>
                            <td>
                                <a href="{{ route('purchase-requests.show', $pr->id) }}" class="btn btn-sm btn-info">Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
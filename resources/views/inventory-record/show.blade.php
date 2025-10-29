@extends('layouts.app')

{{-- Halaman detail pemeriksaan & pencatatan menggunakan Bootstrap --}}
@section('use_bootstrap', true)

@section('title', 'Detail Pemeriksaan & Pencatatan')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5>Detail Pemeriksaan & Pencatatan</h5>
        <a href="{{ route('inventory-records.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4"><strong>No GR:</strong> {{ $record->goodsReceipt->gr_number ?? '-' }}</div>
                <div class="col-md-4"><strong>Tanggal Pemeriksaan:</strong> {{ $record->record_date }}</div>
                <div class="col-md-4"><strong>Petugas:</strong> {{ $record->recorder->name ?? '-' }}</div>
            </div>
            <div class="mb-3">
                <strong>Catatan:</strong>
                <p>{{ $record->notes ?? '-' }}</p>
            </div>
        </div>
    </div>
@endsection
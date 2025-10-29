@extends('layouts.app')

{{-- Halaman daftar pemeriksaan & pencatatan menggunakan Bootstrap --}}
@section('use_bootstrap', true)

@section('title', 'Daftar Pemeriksaan & Pencatatan')

@section('content')
    <h5 class="mb-3">Daftar Pemeriksaan & Pencatatan</h5>
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>No GR</th>
                        <th>Tanggal Pemeriksaan</th>
                        <th>Petugas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($records as $record)
                        <tr>
                            <td>{{ $record->goodsReceipt->gr_number ?? '-' }}</td>
                            <td>{{ $record->record_date }}</td>
                            <td>{{ $record->recorder->name ?? '-' }}</td>
                            <td>
                                <a href="{{ route('inventory-records.show', $record->id) }}" class="btn btn-sm btn-info">Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
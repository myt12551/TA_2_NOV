@extends('layouts.app')

{{-- Halaman form pemeriksaan & pencatatan menggunakan Bootstrap --}}
@section('use_bootstrap', true)

@section('title', 'Pemeriksaan & Pencatatan')

@section('content')
    <h5 class="mb-3">Buat Pemeriksaan & Pencatatan untuk GR {{ $gr->gr_number }}</h5>
    <form action="{{ route('inventory-records.store', $gr->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="record_date" class="form-label">Tanggal Pemeriksaan</label>
            <input type="date" name="record_date" id="record_date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="notes" class="form-label">Catatan</label>
            <textarea name="notes" id="notes" class="form-control" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Pemeriksaan</button>
    </form>
@endsection
@extends('layouts.app')

{{-- Halaman form invoice menggunakan Bootstrap --}}
@section('use_bootstrap', true)

@section('title', 'Unggah Invoice')

@section('content')
    <h5 class="mb-3">Unggah Invoice untuk PO {{ $po->po_number }}</h5>
    <form action="{{ route('invoices.store', $po->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="invoice_number" class="form-label">No Invoice</label>
                <input type="text" name="invoice_number" id="invoice_number" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label for="invoice_date" class="form-label">Tanggal Invoice</label>
                <input type="date" name="invoice_date" id="invoice_date" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label for="due_date" class="form-label">Jatuh Tempo</label>
                <input type="date" name="due_date" id="due_date" class="form-control">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="amount" class="form-label">Jumlah</label>
                <input type="number" step="0.01" name="amount" id="amount" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label for="invoice_file" class="form-label">Lampiran (PDF/Gambar)</label>
                <input type="file" name="invoice_file" id="invoice_file" class="form-control">
            </div>
        </div>
        <div class="mb-3">
            <label for="notes" class="form-label">Catatan</label>
            <textarea name="notes" id="notes" class="form-control" rows="2"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Invoice</button>
    </form>
@endsection
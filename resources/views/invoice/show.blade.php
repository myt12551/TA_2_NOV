@extends('layouts.app')

{{-- Halaman detail invoice menggunakan Bootstrap --}}
@section('use_bootstrap', true)

@section('title', 'Detail Invoice')

@section('content')
    @php use Illuminate\Support\Facades\Storage; @endphp
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5>Detail Invoice</h5>
        <a href="{{ route('invoices.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4"><strong>No Invoice:</strong> {{ $invoice->invoice_number }}</div>
                <div class="col-md-4"><strong>Tanggal:</strong> {{ $invoice->invoice_date }}</div>
                <div class="col-md-4"><strong>Status:</strong> <span class="badge {{ $invoice->status === 'paid' ? 'bg-success' : 'bg-warning' }}">{{ ucfirst($invoice->status) }}</span></div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><strong>Jatuh Tempo:</strong> {{ $invoice->due_date ?? '-' }}</div>
                <div class="col-md-4"><strong>Jumlah:</strong> Rp {{ number_format($invoice->amount, 0, ',', '.') }}</div>
                <div class="col-md-4"><strong>No PO:</strong> {{ $invoice->purchaseOrder->po_number ?? '-' }}</div>
            </div>
            <div class="mb-3">
                <strong>Catatan:</strong>
                <p>{{ $invoice->notes ?? '-' }}</p>
            </div>
            @if ($invoice->invoice_file)
                <div class="mb-3">
                    <strong>Lampiran:</strong>
                    <a href="{{ Storage::url($invoice->invoice_file) }}" target="_blank">Lihat File</a>
                </div>
            @endif

            @if ($invoice->status === 'unpaid')
                <form action="{{ route('invoices.mark-paid', $invoice->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success">Tandai Dibayar</button>
                </form>
            @endif
        </div>
    </div>
@endsection
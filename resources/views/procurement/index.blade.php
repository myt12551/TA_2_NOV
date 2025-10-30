@extends('layouts.app')

{{-- Gunakan Bootstrap untuk tampilan ini --}}
@section('use_bootstrap', true)

@section('title', 'Dashboard Pengadaan')

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Alur Pengadaan: PR → PO → GR → Pemeriksaan &amp; Pencatatan → Pembayaran</h5>
            <a href="{{ route('purchase-requests.create') }}" class="btn btn-primary btn-sm">
                Buat Permintaan Pembelian
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Permintaan Pembelian (PR)</th>
                            <th>Pemesanan ke Supplier (PO)</th>
                            <th>Penerimaan Barang</th>
                            <th>Pemeriksaan &amp; Pencatatan</th>
                            <th>Pembayaran Supplier</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchaseRequests as $pr)
                            @php
                                $po        = $pr->purchaseOrders->first();
                                $gr        = $po?->goodsReceipts->first() ?? null;
                                $invoice   = $po?->invoices->first() ?? null;
                                $inspection = $gr?->inventoryRecords->first() ?? null;
                            @endphp
                            <tr>
                                {{-- PR column --}}
                                <td>
                                    <div><strong>No PR:</strong> {{ $pr->pr_number }}</div>
                                    <div><strong>Tanggal:</strong> {{ $pr->request_date }}</div>
                                    <div><strong>Status:</strong>
                                        <span class="badge {{ $pr->status === 'approved' ? 'bg-success' : ($pr->status === 'rejected' ? 'bg-danger' : 'bg-secondary') }}">
                                            {{ ucfirst($pr->status) }}
                                        </span>
                                    </div>
                                    <div class="mt-2">
                                        <a href="{{ route('purchase-requests.show', $pr->id) }}" class="btn btn-sm btn-info mb-1">Detail PR</a>
                                        @if ($pr->status === 'pending')
                                            <form action="{{ route('purchase-requests.approve', $pr->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success mb-1">Setujui</button>
                                            </form>
                                            <form action="{{ route('purchase-requests.reject', $pr->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger mb-1">Tolak</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                                {{-- PO column --}}
                                <td class="text-center">
                                    @if (!$po && $pr->status === 'approved')
                                        <a href="{{ route('new-purchase-orders.create', ['pr' => $pr->id]) }}" class="btn btn-sm btn-primary">Buat PO</a>
                                    @elseif ($po)
                                        <div><strong>No PO:</strong> {{ $po->po_number }}</div>
                                        <div><strong>Status:</strong>
                                            <span class="badge {{ $po->status === 'validated' || $po->status === 'received' ? 'bg-success' : 'bg-secondary' }}">
                                                {{ ucfirst($po->status) }}
                                            </span>
                                        </div>
                                        <div class="mt-1">
                                            <a href="{{ route('new-purchase-orders.show', $po->id) }}" class="btn btn-sm btn-info mb-1">Detail PO</a>
                                            @if ($po->status === 'draft')
                                                <form action="{{ route('new-purchase-orders.confirm', $po->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-warning mb-1">Konfirmasi Supplier</button>
                                                </form>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                {{-- Penerimaan Barang column --}}
                                <td class="text-center">
                                    @if ($po)
                                        @if (!$gr && $po->status === 'validated')
                                            <a href="{{ route('goods-receipts.create', $po->id) }}" class="btn btn-sm btn-secondary">Buat GR</a>
                                        @elseif ($gr)
                                            <div><strong>No GR:</strong> {{ $gr->gr_number }}</div>
                                            <div><strong>Tanggal:</strong> {{ $gr->receipt_date }}</div>
                                            <div class="mt-1">
                                                <a href="{{ route('goods-receipts.show', $gr->id) }}" class="btn btn-sm btn-info mb-1">Detail GR</a>
                                                {{-- Tautan DO dan BBM: gunakan facade Storage secara langsung tanpa import --}}
                                                @if ($gr->delivery_order_file)
                                                    <a href="{{ Illuminate\Support\Facades\Storage::url($gr->delivery_order_file) }}" target="_blank" class="btn btn-sm btn-outline-primary mb-1">Lihat DO</a>
                                                @endif
                                                @if ($gr->bbm_file)
                                                    <a href="{{ Illuminate\Support\Facades\Storage::url($gr->bbm_file) }}" target="_blank" class="btn btn-sm btn-outline-primary mb-1">Lihat BBM</a>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                {{-- Pemeriksaan & Pencatatan column --}}
                                <td class="text-center">
                                    @if ($gr)
                                        @if (!$inspection)
                                            <a href="{{ route('inventory-records.create', $gr->id) }}" class="btn btn-sm btn-secondary">Buat Pencatatan</a>
                                        @else
                                            <div><strong>Tanggal:</strong> {{ $inspection->record_date }}</div>
                                            <a href="{{ route('inventory-records.show', $inspection->id) }}" class="btn btn-sm btn-info mt-1">Detail Pencatatan</a>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                {{-- Pembayaran Supplier column --}}
                                <td class="text-center">
                                    @if ($po)
                                        @if (!$invoice && $po->status === 'received' && $inspection)
                                            <a href="{{ route('invoices.create', $po->id) }}" class="btn btn-sm btn-secondary">Unggah Invoice</a>
                                        @elseif ($invoice)
                                            <div><strong>No Invoice:</strong> {{ $invoice->invoice_number ?? '-' }}</div>
                                            <div><strong>Jumlah:</strong> {{ number_format($invoice->amount, 0, ',', '.') }}</div>
                                            <div class="mt-1">
                                                <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-sm btn-info mb-1">Detail Invoice</a>
                                                @if ($invoice->status === 'unpaid')
                                                    <form action="{{ route('invoices.mark-paid', $invoice->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success mb-1">Tandai Dibayar</button>
                                                    </form>
                                                @else
                                                    <span class="badge bg-success">Lunas</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
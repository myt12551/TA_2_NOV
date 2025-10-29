@extends('layouts.app')

@section('title', 'Analisis Pergerakan Barang')

@push('styles')
<style>
    body {
        background-color: #0f1729;
        color: #fff;
    }
    .page-header {
        background-color: #0f1729;
        padding: 1rem 0;
        margin-bottom: 1rem;
    }
    .content-wrapper {
        background-color: #0f1729;
        min-height: calc(100vh - 60px);
        padding: 1rem;
    }
    .table {
        color: #fff;
        margin-top: 1rem;
    }
    .table thead th {
        background-color: #1a2234;
        border-bottom: none;
        color: #fff;
        padding: 1rem;
    }
    .table td {
        padding: 0.75rem;
        vertical-align: middle;
    }
    .table-hover tbody tr:hover {
        background-color: #1a2234;
    }
    .form-control, .form-select {
        background-color: #1a2234;
        border: 1px solid #2d3748;
        color: #fff;
    }
    .form-control:focus, .form-select:focus {
        background-color: #1a2234;
        border-color: #4c51bf;
        color: #fff;
    }
    .btn-primary {
        background-color: #4c51bf;
        border: none;
    }
    .btn-success {
        background-color: #48bb78;
        border: none;
    }
    .search-box {
        max-width: 250px;
    }
    .header-actions {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }
    .entries-select {
        width: 80px;
    }
</style>
@endpush

@section('content')
<div class="content-wrapper">
    <h2 class="mb-4">Analisis Pergerakan Barang</h2>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex gap-3 align-items-center">
            <div class="d-flex align-items-center gap-2">
                <span>Tampilkan</span>
                <select class="form-select form-select-sm entries-select">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span>entri</span>
            </div>
            <select id="statusFilter" class="form-select form-select-sm">
                <option value="all" {{ $selectedStatus == 'all' ? 'selected' : '' }}>Semua Status</option>
                <option value="fast" {{ $selectedStatus == 'fast' ? 'selected' : '' }}>Fast Moving</option>
                <option value="normal" {{ $selectedStatus == 'normal' ? 'selected' : '' }}>Normal</option>
                <option value="slow" {{ $selectedStatus == 'slow' ? 'selected' : '' }}>Slow Moving</option>
            </select>
        </div>
        <div class="header-actions">
            <div class="search-box">
                <input type="search" class="form-control form-control-sm" placeholder="Cari...">
            </div>
            <a href="{{ route('inventory.stock-movement.analyze') }}" class="btn btn-primary btn-sm">Update</a>
            <a href="{{ route('inventory.stock-movement.export') }}" class="btn btn-success btn-sm">Export</a>
        </div>
    </div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

    <div class="table-responsive">
        <table class="table table-hover" id="stockMovementTable">
            <thead>
                <tr>
                    <th style="width: 60px">No</th>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th class="text-end" style="width: 120px">Modal</th>
                    <th class="text-end" style="width: 120px">Harga</th>
                    <th class="text-center" style="width: 80px">Stok</th>
                    <th class="text-center" style="width: 100px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($analyses as $analysis)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $analysis->code }}</td>
                    <td>{{ $analysis->name }}</td>
                    <td>{{ $analysis->category->name }}</td>
                    <td class="text-end">Rp {{ number_format($analysis->cost_price, 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($analysis->selling_price, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $analysis->stock }}</td>
                    <td class="text-center">
                        <div class="btn-group">
                            <button type="button" 
                                    class="btn btn-sm btn-success"
                                    onclick="window.location.href='{{ route('inventory.stock-movement.fast-moving') }}'"
                                    title="Lihat fast moving">
                                <i class="bi bi-graph-up"></i>
                            </button>
                            <button type="button" 
                                    class="btn btn-sm btn-danger"
                                    onclick="window.location.href='{{ route('inventory.stock-movement.slow-moving') }}'"
                                    title="Lihat slow moving">
                                <i class="bi bi-graph-down"></i>
                            </button>
                            <button type="button" 
                                    class="btn btn-sm btn-info"
                                    onclick="window.location.href='{{ route('inventory.stock-movement.settings') }}'"
                                    title="Pengaturan">
                                <i class="bi bi-gear"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</div>

@push('scripts')
<!-- jQuery & DataTables -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#stockMovementTable').DataTable({
        pageLength: 10,
        ordering: true,
        searching: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json'
        },
        dom: "<'row'<'col-sm-12'tr>>" +
             "<'row mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
    });

    // Move DataTable search to custom search box
    $('.search-box input').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Move DataTable length menu to custom select
    $('.entries-select').on('change', function() {
        table.page.len($(this).val()).draw();
    });

    // Status Filter
    $('#statusFilter').change(function() {
        window.location.href = "{{ route('inventory.stock-movement.index') }}?status=" + $(this).val();
    });
});

// Status Filter
$('#statusFilter').change(function() {
    window.location.href = "{{ route('inventory.stock-movement.index') }}?status=" + $(this).val();
});
</script>
@endpush

@endsection
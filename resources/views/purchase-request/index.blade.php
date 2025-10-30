@extends('layouts.app')

{{-- Halaman ini menggunakan Bootstrap --}}
@section('use_bootstrap', true)

@section('title', 'Daftar Permintaan Pembelian')

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5>Daftar Permintaan Pembelian</h5>
        @if(auth()->user()->hasRole(['admin', 'warehouse']))
            <a href="{{ route('purchase-requests.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah PR
            </a>
        @endif
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h6 class="mb-0">Daftar PR</h6>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm" id="searchInput" placeholder="Cari PR...">
                        <button class="btn btn-outline-secondary btn-sm" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0" id="prTable">
                    <thead>
                        <tr>
                            <th>No PR</th>
                            <th>Tanggal</th>
                            <th>Pemohon</th>
                            <th>Status</th>
                            <th>Items</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $pr)
                            <tr>
                                <td>{{ $pr->pr_number }}</td>
                                <td>{{ optional($pr->request_date)->format('d/m/Y') }}</td>
                                <td>{{ optional($pr->requester)->name }}</td>
                                <td>
                                    @php
                                        $statusClass = match($pr->status) {
                                            'pending' => 'bg-warning',
                                            'approved' => 'bg-success',
                                            'rejected' => 'bg-danger',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }}">{{ ucfirst($pr->status) }}</span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#itemsModal{{ $pr->id }}">
                                        {{ $pr->items->count() }} items
                                    </button>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('purchase-requests.show', $pr->id) }}" 
                                           class="btn btn-info btn-sm">
                                            Detail
                                        </a>
                                        
                                        @if($pr->status === 'pending')
                                            @if(auth()->user()->isSupervisor())
                                                <button type="button" 
                                                        class="btn btn-success btn-sm"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#approveModal{{ $pr->id }}">
                                                    Setujui PR
                                                </button>
                                                <button type="button" 
                                                        class="btn btn-danger btn-sm"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#rejectModal{{ $pr->id }}">
                                                    Tolak PR
                                                </button>
                                            @else
                                                <span class="badge bg-warning">Menunggu Persetujuan Supervisor</span>
                                            @endif
                                        @endif

                                        @if($pr->status === 'approved')
                                            @if(auth()->user()->hasRole(['admin', 'supervisor']))
                                                <a href="{{ route('new-purchase-orders.create', $pr->id) }}" 
                                                   class="btn btn-primary btn-sm">
                                                    Buat PO
                                                </a>
                                            @endif
                                            <span class="badge bg-success">Disetujui</span>
                                        @endif

                                        @if($pr->status === 'rejected')
                                            <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            <!-- Items Modal -->
                            <div class="modal fade" id="itemsModal{{ $pr->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Items PR #{{ $pr->pr_number }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Produk</th>
                                                            <th>Jumlah</th>
                                                            <th>Satuan</th>
                                                            <th>Catatan</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($pr->items as $item)
                                                            <tr>
                                                                <td>{{ $item->product_name }}</td>
                                                                <td>{{ $item->quantity }}</td>
                                                                <td>{{ $item->unit }}</td>
                                                                <td>{{ $item->notes ?: '-' }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Approve Modal -->
                            <div class="modal fade" id="approveModal{{ $pr->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('purchase-requests.approve', $pr->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">Setujui PR #{{ $pr->pr_number }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label required">Dokumen Validasi</label>
                                                    <input type="file" 
                                                           class="form-control @error('validation_document') is-invalid @enderror" 
                                                           name="validation_document"
                                                           accept=".pdf,.jpg,.jpeg,.png"
                                                           required>
                                                    <div class="form-text">Upload dokumen validasi (PDF/JPG/PNG)</div>
                                                    @error('validation_document')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Catatan Persetujuan</label>
                                                    <textarea class="form-control" 
                                                              name="approval_notes" 
                                                              rows="3"
                                                              placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fas fa-check"></i> Setujui PR
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Reject Modal -->
                            <div class="modal fade" id="rejectModal{{ $pr->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('purchase-requests.reject', $pr->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">Tolak PR #{{ $pr->pr_number }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label required">Alasan Penolakan</label>
                                                    <textarea class="form-control @error('rejection_reason') is-invalid @enderror" 
                                                              name="rejection_reason" 
                                                              rows="3"
                                                              required></textarea>
                                                    @error('rejection_reason')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fas fa-times"></i> Tolak PR
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#prTable').DataTable({
                "pageLength": 10,
                "order": [[1, 'desc']], // Sort by date descending
                "language": {
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "zeroRecords": "Tidak ada data yang ditemukan",
                    "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                    "infoEmpty": "Tidak ada data yang tersedia",
                    "infoFiltered": "(difilter dari _MAX_ total data)",
                    "search": "Cari:",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                }
            });

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Custom search box
            $('#searchInput').keyup(function(){
                table.search($(this).val()).draw();
            });
        });
    </script>

    <style>
        .required:after {
            content: ' *';
            color: red;
        }
        .btn-group {
            gap: 0.25rem;
        }
        .badge {
            font-size: 0.85em;
            padding: 0.35em 0.65em;
        }
    </style>
@endsection
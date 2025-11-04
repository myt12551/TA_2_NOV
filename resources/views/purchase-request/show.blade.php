<x-layout>
    <x-slot:title>Detail Permintaan Pembelian</x-slot:title>
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Detail Permintaan Pembelian</h4>
                        <div>
                            <a href="{{ route('purchase-requests.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            @if($pr->status === 'pending' && auth()->user()->role === 'supervisor')
                                <button type="button" class="btn btn-success ms-2" data-bs-toggle="modal" data-bs-target="#approveModal">
                                    <i class="fas fa-check"></i> Setujui
                                </button>
                                <button type="button" class="btn btn-danger ms-2" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                    <i class="fas fa-times"></i> Tolak
                                </button>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <table class="table table-sm">
                                    <tr>
                                        <td style="width: 150px"><strong>Nomor PR</strong></td>
                                        <td>: {{ $pr->pr_number }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tanggal</strong></td>
                                        <td>: {{ $pr->request_date->format('d/m/Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status</strong></td>
                                        <td>: 
                                            @if($pr->status === 'pending')
                                                <span class="badge bg-warning">Menunggu</span>
                                            @elseif($pr->status === 'approved')
                                                <span class="badge bg-success">Disetujui</span>
                                            @else
                                                <span class="badge bg-danger">Ditolak</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-sm">
                                    <tr>
                                        <td style="width: 150px"><strong>Supplier</strong></td>
                                        <td>: {{ $pr->supplier->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Diminta Oleh</strong></td>
                                        <td>: {{ $pr->requester->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Catatan</strong></td>
                                        <td>: {{ $pr->notes ?: '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 50px">No</th>
                                        <th>Nama Barang</th>
                                        <th style="width: 150px">Jumlah</th>
                                        <th style="width: 150px">Satuan</th>
                                        <th>Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pr->items as $index => $item)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $item->product_name }}</td>
                                            <td class="text-end">{{ number_format($item->quantity) }}</td>
                                            <td>{{ $item->unit }}</td>
                                            <td>{{ $item->notes ?: '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($pr->status !== 'pending')
                            <div class="mt-4">
                                <h5>Informasi Approval</h5>
                                <table class="table table-sm">
                                    <tr>
                                        <td style="width: 150px"><strong>Diproses Oleh</strong></td>
                                        <td>: {{ $pr->approver->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tanggal Proses</strong></td>
                                        <td>: {{ $pr->approved_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    @if($pr->status === 'approved')
                                        <tr>
                                            <td><strong>Catatan</strong></td>
                                            <td>: {{ $pr->approval_notes ?: '-' }}</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td><strong>Alasan Penolakan</strong></td>
                                            <td>: {{ $pr->rejection_reason }}</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($pr->status === 'pending' && auth()->user()->role === 'supervisor')
        <!-- Modal Approval -->
        <div class="modal fade" id="approveModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('purchase-requests.approve', $pr->id) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Konfirmasi Persetujuan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>Apakah Anda yakin ingin menyetujui permintaan pembelian ini?</p>
                            <div class="form-group">
                                <label class="form-label">Catatan (opsional)</label>
                                <textarea name="approval_notes" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success">Ya, Setujui</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Reject -->
        <div class="modal fade" id="rejectModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('purchase-requests.reject', $pr->id) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Konfirmasi Penolakan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                                <textarea name="rejection_reason" class="form-control" rows="3" required></textarea>
                                <small class="text-muted">
                                    Minimal 10 karakter untuk menjelaskan alasan penolakan
                                </small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Ya, Tolak</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</x-layout>
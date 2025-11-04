<x-layout>
    <x-slot:title>Daftar Purchase Order</x-slot:title>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="mb-3">
                    <a href="{{ route('purchase-requests.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Buat Purchase Request Baru
                    </a>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Purchase Request yang Disetujui</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nomor PR</th>
                                        <th>Tanggal</th>
                                        <th>Supplier</th>
                                        <th>Diminta Oleh</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($approvedPRs as $pr)
                                        @if($pr->status === 'approved' && !$pr->purchaseOrders()->exists())
                                            <tr>
                                                <td>{{ $pr->pr_number }}</td>
                                                <td>{{ $pr->request_date->format('d/m/Y') }}</td>
                                                <td>{{ $pr->supplier->name }}</td>
                                                <td>{{ $pr->requester->name }}</td>
                                                <td>
                                                    <span class="badge bg-success">Disetujui</span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('new-purchase-orders.create', $pr->id) }}" 
                                                       class="btn btn-primary btn-sm">
                                                        <i class="fas fa-plus"></i> Buat PO
                                                    </a>
                                                </td>
                                            </tr>
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">
                                                Tidak ada PR yang disetujui dan siap diproses
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Daftar Purchase Order</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nomor PO</th>
                                        <th>Tanggal</th>
                                        <th>Supplier</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($ongoingPOs as $po)
                                        <tr>
                                            <td>{{ $po->po_number }}</td>
                                            <td>{{ $po->po_date->format('d/m/Y') }}</td>
                                            <td>{{ $po->supplier->name }}</td>
                                            <td class="text-end">{{ number_format($po->total_amount) }}</td>
                                            <td>
                                                @if($po->status === 'draft')
                                                    <span class="badge bg-warning">Draft</span>
                                                @elseif($po->status === 'sent')
                                                    <span class="badge bg-info">Terkirim</span>
                                                @elseif($po->status === 'confirmed')
                                                    <span class="badge bg-primary">Dikonfirmasi</span>
                                                @elseif($po->status === 'received')
                                                    <span class="badge bg-success">Diterima</span>
                                                @elseif($po->status === 'invoiced')
                                                    <span class="badge bg-secondary">Ditagih</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('new-purchase-orders.show', $po->id) }}"
                                                       class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('new-purchase-orders.pdf', $po->id) }}"
                                                       class="btn btn-secondary btn-sm">
                                                        <i class="fas fa-file-pdf"></i>
                                                    </a>
                                                    @if($po->status === 'draft')
                                                        <form action="{{ route('new-purchase-orders.mark-sent', $po->id) }}"
                                                              method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-primary btn-sm"
                                                                    onclick="return confirm('Tandai PO ini sebagai terkirim?')">
                                                                <i class="fas fa-paper-plane"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">
                                                Tidak ada PO yang sedang diproses
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
<x-layout>
    <!--
      View: Daftar Purchase Order
      Tampilan ini menggunakan card dan table Bootstrap agar serasi dengan modul lainnya.
      Tombol "Buat PO Baru" ditempatkan di header kartu dan daftar PO ditampilkan
      dalam tabel responsif.
    -->
    <x-slot:title>Daftar Purchase Order</x-slot:title>

    @if (session('success'))
        <x-alert type="success" :message="session('success')" />
    @endif

    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Purchase Order</h5>
                    <div>
                        <a href="{{ route('purchase-requests.create') }}" class="btn btn-primary btn-sm me-2">
                            Buat Permintaan Pembelian (PR)
                        </a>
                        <form action="{{ route('purchase-orders.delete-all') }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus semua Purchase Order?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus Semua PO</button>
                        </form>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No PO</th>
                                    <th>Supplier</th>
                                    <th>Tanggal</th>
                                    <th>Status PO</th>
                                    <th>No PR</th>
                                    <th>Status PR</th>
                                    <th>Penerimaan Barang (GR)</th>
                                    <th>Invoice</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $po)
                                    <tr>
                                        <td>{{ $po->po_number }}</td>
                                        <td>{{ $po->supplier->name }}</td>
                                        <td>{{ $po->po_date }}</td>
                                        <td>
                                            @php
                                                $badgeClass = match($po->status) {
                                                    'draft' => 'bg-warning text-dark',
                                                    'validated' => 'bg-primary',
                                                    'received' => 'bg-success',
                                                    'completed' => 'bg-success',
                                                    default => 'bg-secondary'
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">{{ ucfirst($po->status) }}</span>
                                        </td>
                                        <td>
                                            @if($po->purchaseRequest)
                                                <span class="badge bg-info">{{ $po->purchaseRequest->pr_number }}</span>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($po->purchaseRequest)
                                                <span class="badge {{ $po->purchaseRequest->status === 'approved' ? 'bg-success' : ($po->purchaseRequest->status === 'rejected' ? 'bg-danger' : 'bg-secondary') }}">{{ ucfirst($po->purchaseRequest->status) }}</span>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($po->goodsReceipts && $po->goodsReceipts->count())
                                                <span class="badge bg-success">{{ $po->goodsReceipts->first()->gr_number ?? 'Sudah Diterima' }}</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Belum Diterima</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($po->invoices && $po->invoices->count())
                                                <span class="badge bg-success">{{ $po->invoices->first()->invoice_number ?? 'Ada Invoice' }}</span>
                                            @else
                                                <span class="badge bg-secondary">Belum Ada</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('purchase-orders.show', $po->id) }}" class="btn btn-info btn-sm me-1">Detail</a>
                                            @if($po->status === 'draft')
                                                <form action="{{ route('purchase-orders.validate', $po->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-warning btn-sm">Konfirmasi Supplier</button>
                                                </form>
                                            @endif
                                            @if($po->goodsReceipts->isEmpty() && $po->status === 'validated')
                                                <a href="{{ route('goods-receipts.create', $po->id) }}" class="btn btn-secondary btn-sm">Buat GR</a>
                                            @endif
                                            @if($po->goodsReceipts->count() && $po->invoices->isEmpty() && $po->status === 'received')
                                                <a href="{{ route('invoices.create', $po->id) }}" class="btn btn-secondary btn-sm">Unggah Invoice</a>
                                            @endif
                                            <a href="{{ action([\App\Http\Controllers\PurchaseOrderController::class, 'exportPDF'], $po->id) }}" class="btn btn-success btn-sm">Cetak PDF</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
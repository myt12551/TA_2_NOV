<x-layout>
    <x-slot:title>Detail Purchase Order</x-slot:title>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Purchase Order #{{ $purchaseOrder->po_number }}</h5>
                        <div class="btn-group">
                            <a href="{{ route('new-purchase-orders.pdf', $purchaseOrder->id) }}" 
                               class="btn btn-secondary">
                                <i class="fas fa-file-pdf"></i> PDF
                            </a>
                            @if($purchaseOrder->status === 'draft')
                                <form action="{{ route('new-purchase-orders.mark-sent', $purchaseOrder->id) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-primary"
                                            onclick="return confirm('Tandai PO ini sebagai terkirim?')">
                                        <i class="fas fa-paper-plane"></i> Kirim ke Supplier
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="mb-3">Informasi PO:</h6>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td style="width: 150px">Nomor PO</td>
                                        <td>: {{ $purchaseOrder->po_number }}</td>
                                    </tr>
                                    <tr>
                                        <td>Tanggal PO</td>
                                        <td>: {{ $purchaseOrder->po_date->format('d/m/Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Status</td>
                                        <td>: 
                                            @if($purchaseOrder->status === 'draft')
                                                <span class="badge bg-warning">Draft</span>
                                            @elseif($purchaseOrder->status === 'sent')
                                                <span class="badge bg-info">Terkirim</span>
                                            @elseif($purchaseOrder->status === 'confirmed')
                                                <span class="badge bg-primary">Dikonfirmasi</span>
                                            @elseif($purchaseOrder->status === 'received')
                                                <span class="badge bg-success">Diterima</span>
                                            @elseif($purchaseOrder->status === 'invoiced')
                                                <span class="badge bg-secondary">Ditagih</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Dibuat Oleh</td>
                                        <td>: {{ $purchaseOrder->creator->name }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6 class="mb-3">Informasi Supplier:</h6>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td style="width: 150px">Nama</td>
                                        <td>: {{ $purchaseOrder->supplier->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Alamat</td>
                                        <td>: {{ $purchaseOrder->supplier->address }}</td>
                                    </tr>
                                    <tr>
                                        <td>Telepon</td>
                                        <td>: {{ $purchaseOrder->supplier->phone }}</td>
                                    </tr>
                                    <tr>
                                        <td>Email</td>
                                        <td>: {{ $purchaseOrder->supplier->email }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="table-responsive mb-3">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th>Jumlah</th>
                                        <th>Satuan</th>
                                        <th>Harga</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($purchaseOrder->items as $item)
                                        <tr>
                                            <td>{{ $item->supplierProduct->name }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ $item->supplierProduct->unit }}</td>
                                            <td class="text-end">{{ number_format($item->price) }}</td>
                                            <td class="text-end">{{ number_format($item->quantity * $item->price) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-end">Total</th>
                                        <th class="text-end">{{ number_format($purchaseOrder->total_amount) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        @if($purchaseOrder->notes)
                            <div class="mb-3">
                                <h6>Catatan:</h6>
                                <p class="mb-0">{{ $purchaseOrder->notes }}</p>
                            </div>
                        @endif

                        <div class="mt-4">
                            <h6>Riwayat Status:</h6>
                            <ul class="list-unstyled">
                                <li>
                                    <i class="fas fa-check text-success"></i>
                                    Dibuat pada {{ $purchaseOrder->created_at->format('d/m/Y H:i') }}
                                    oleh {{ $purchaseOrder->creator->name }}
                                </li>
                                @if($purchaseOrder->status !== 'draft')
                                    <li>
                                        <i class="fas fa-paper-plane text-info"></i>
                                        Dikirim ke supplier pada {{ $purchaseOrder->sent_at->format('d/m/Y H:i') }}
                                        oleh {{ $purchaseOrder->sender->name }}
                                    </li>
                                @endif
                                @if($purchaseOrder->status === 'confirmed')
                                    <li>
                                        <i class="fas fa-thumbs-up text-primary"></i>
                                        Dikonfirmasi supplier pada {{ $purchaseOrder->confirmed_at->format('d/m/Y H:i') }}
                                    </li>
                                @endif
                                @if($purchaseOrder->status === 'received')
                                    <li>
                                        <i class="fas fa-box text-success"></i>
                                        Barang diterima pada {{ $purchaseOrder->received_at->format('d/m/Y H:i') }}
                                        oleh {{ $purchaseOrder->receiver->name }}
                                    </li>
                                @endif
                                @if($purchaseOrder->status === 'invoiced')
                                    <li>
                                        <i class="fas fa-file-invoice text-secondary"></i>
                                        Faktur dibuat pada {{ $purchaseOrder->invoiced_at->format('d/m/Y H:i') }}
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>

                @if($purchaseOrder->status === 'sent' || $purchaseOrder->status === 'confirmed')
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">Penerimaan Barang</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('goods-receipts.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="purchase_order_id" value="{{ $purchaseOrder->id }}">
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label required">Nomor Surat Jalan</label>
                                            <input type="text" name="delivery_number" 
                                                   class="form-control @error('delivery_number') is-invalid @enderror"
                                                   value="{{ old('delivery_number') }}" required>
                                            @error('delivery_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label required">Tanggal Terima</label>
                                            <input type="date" name="receipt_date" 
                                                   class="form-control @error('receipt_date') is-invalid @enderror"
                                                   value="{{ old('receipt_date', date('Y-m-d')) }}" required>
                                            @error('receipt_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive mb-3">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Produk</th>
                                                <th>Jumlah PO</th>
                                                <th>Satuan</th>
                                                <th>Jumlah Diterima</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($purchaseOrder->items as $item)
                                                <tr>
                                                    <td>
                                                        {{ $item->supplierProduct->name }}
                                                        <input type="hidden" 
                                                               name="items[{{ $loop->index }}][purchase_order_item_id]"
                                                               value="{{ $item->id }}">
                                                    </td>
                                                    <td>{{ $item->quantity }}</td>
                                                    <td>{{ $item->supplierProduct->unit }}</td>
                                                    <td>
                                                        <input type="number" 
                                                               name="items[{{ $loop->index }}][quantity_received]"
                                                               class="form-control"
                                                               value="{{ old("items.{$loop->index}.quantity_received", $item->quantity) }}"
                                                               min="0" max="{{ $item->quantity }}" required>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Catatan</label>
                                    <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Terima Barang
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layout>
<x-layout>
    <x-slot:title>Buat Purchase Order</x-slot:title>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Buat Purchase Order dari PR #{{ $purchaseRequest->pr_number }}</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('new-purchase-orders.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="purchase_request_id" value="{{ $purchaseRequest->id }}">

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nomor PR</label>
                                        <input type="text" class="form-control" value="{{ $purchaseRequest->pr_number }}" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal PR</label>
                                        <input type="text" class="form-control" value="{{ $purchaseRequest->request_date->format('d/m/Y') }}" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Diminta Oleh</label>
                                        <input type="text" class="form-control" value="{{ $purchaseRequest->requester->name }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Nomor PO</label>
                                        <input type="text" name="po_number" class="form-control @error('po_number') is-invalid @enderror" 
                                               value="{{ old('po_number') }}" required>
                                        @error('po_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label required">Tanggal PO</label>
                                        <input type="date" name="po_date" class="form-control @error('po_date') is-invalid @enderror"
                                               value="{{ old('po_date', date('Y-m-d')) }}" required>
                                        @error('po_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Supplier</label>
                                        <input type="text" class="form-control" value="{{ $purchaseRequest->supplier->name }}" readonly>
                                    </div>
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
                                        @foreach($purchaseRequest->items as $item)
                                            <tr>
                                                <td>
                                                    {{ $item->supplierProduct->name }}
                                                    <input type="hidden" name="items[{{ $loop->index }}][supplier_product_id]"
                                                           value="{{ $item->supplier_product_id }}">
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" 
                                                           name="items[{{ $loop->index }}][quantity]"
                                                           value="{{ $item->quantity }}" readonly>
                                                </td>
                                                <td>{{ $item->supplierProduct->unit }}</td>
                                                <td>
                                                    <input type="number" class="form-control" 
                                                           name="items[{{ $loop->index }}][price]"
                                                           value="{{ $item->price }}" required
                                                           min="0" step="1">
                                                </td>
                                                <td class="text-end">
                                                    <span class="item-total">
                                                        {{ number_format($item->quantity * $item->price) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" class="text-end">Total</th>
                                            <th class="text-end">
                                                <span id="grand-total">0</span>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Catatan</label>
                                <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                            </div>

                            <div class="text-end">
                                <a href="{{ route('new-purchase-orders.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function updateTotals() {
            let grandTotal = 0;
            document.querySelectorAll('tbody tr').forEach(row => {
                const quantity = parseFloat(row.querySelector('input[name$="[quantity]"]').value) || 0;
                const price = parseFloat(row.querySelector('input[name$="[price]"]').value) || 0;
                const total = quantity * price;
                
                row.querySelector('.item-total').textContent = total.toLocaleString();
                grandTotal += total;
            });
            
            document.getElementById('grand-total').textContent = grandTotal.toLocaleString();
        }

        document.querySelectorAll('input[name$="[price]"]').forEach(input => {
            input.addEventListener('input', updateTotals);
        });

        updateTotals();
    </script>
    @endpush
</x-layout>
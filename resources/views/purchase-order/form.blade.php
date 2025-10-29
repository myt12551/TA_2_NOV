@extends('layouts.app')

@section('title', 'Buat Purchase Order')

@section('use_bootstrap', true)

@section('content')
    <h5 class="mb-3">Buat Purchase Order</h5>
    <!--
        Formulir pembuatan Purchase Order.
        - Jika halaman ini diakses melalui tahapan PR (purchase_request_id tersedia),
          maka daftar item akan diisi dengan item dari PR tersebut.
        - Jika tidak, maka saat pengguna memilih supplier, daftar produk akan
          dimuat otomatis dari supplier via route `purchase-orders.get-items`.
    -->
    <form action="{{ route('purchase-orders.store') }}" method="POST">
        @csrf
        @if(isset($pr))
            <input type="hidden" name="purchase_request_id" value="{{ $pr->id }}">
        @endif
        <div class="row mb-3">
            <div class="col-md-4">
                @if(isset($pr) && isset($pr->supplier_id))
                    @php
                        $supplier = $suppliers->where('id', $pr->supplier_id)->first();
                    @endphp
                    <label class="form-label">Supplier</label>
                    <input type="text" class="form-control" value="{{ $supplier ? $supplier->name : '-' }}" readonly>
                    <input type="hidden" name="supplier_id" value="{{ $pr->supplier_id }}">
                @else
                    <label for="supplier_id" class="form-label">Supplier</label>
                    <select name="supplier_id" id="supplier_id" class="form-select" required>
                        <option value="">-- Pilih Supplier --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                @endif
            </div>
            <div class="col-md-4">
                <label for="po_date" class="form-label">Tanggal PO</label>
                <input type="date" name="po_date" id="po_date" class="form-control" required>
            </div>
        </div>
        <h6>Daftar Item</h6>
        <table class="table" id="poItemsTable">
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Stok Sekarang</th>
                    <th>Satuan</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                {{-- Jika ada PR, tampilkan item dari PR --}}
                @if(isset($pr) && $pr->items->count())
                    @foreach($pr->items as $index => $item)
                        @php
                            $itemData = \App\Models\Item::where('name', $item->product_name)->first();
                            $units = $itemData && $itemData->category ? [$itemData->category->name] : ['pcs','box','kg','liter'];
                        @endphp
                        <tr>
                            <td><input type="text" name="items[{{ $index }}][product_name]" value="{{ $item->product_name }}" class="form-control" required></td>
                            <td><input type="number" name="items[{{ $index }}][quantity]" value="{{ $item->quantity }}" class="form-control" required></td>
                            <td><input type="number" step="0.01" name="items[{{ $index }}][unit_price]" class="form-control" required></td>
                            <td>{{ $itemData ? $itemData->stock : '-' }}</td>
                            <td>
                                <select name="items[{{ $index }}][unit]" class="form-select">
                                    <option value="pcs">pcs</option>
                                    <option value="box">box</option>
                                    <option value="kg">kg</option>
                                    <option value="liter">liter</option>
                                </select>
                            </td>
                            <td></td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td><input type="text" name="items[0][product_name]" class="form-control" required></td>
                        <td><input type="number" name="items[0][quantity]" class="form-control" required></td>
                        <td><input type="number" step="0.01" name="items[0][unit_price]" class="form-control" required></td>
                        <td>-</td>
                        <td>
                            <select name="items[0][unit]" class="form-select">
                                <option value="pcs">pcs</option>
                                <option value="box">box</option>
                                <option value="kg">kg</option>
                                <option value="liter">liter</option>
                            </select>
                        </td>
                        <td></td>
                    </tr>
                @endif
            </tbody>
        </table>
        <div class="mb-3">
            <button type="button" id="addPoRow" class="btn btn-sm btn-secondary">Tambah Item</button>
        </div>
        <button type="submit" class="btn btn-primary">Simpan PO</button>
    </form>

    @push('scripts')
        <script>
            (function() {
                const supplierSelect = document.getElementById('supplier_id');
                const itemsTableBody = document.getElementById('poItemsTable').getElementsByTagName('tbody')[0];
                const addRowButton = document.getElementById('addPoRow');
                // Utility: Add a blank row
                function addBlankRow() {
                    const index = itemsTableBody.rows.length;
                    const row = itemsTableBody.insertRow();
                    row.innerHTML = `<td><input type="text" name="items[${index}][product_name]" class="form-control" required></td>
                        <td><input type="number" name="items[${index}][quantity]" class="form-control" required></td>
                        <td><input type="number" step="0.01" name="items[${index}][unit_price]" class="form-control" required></td>
                        <td>-</td>
                        <td><select name="items[${index}][unit]" class="form-select"><option value="pcs">pcs</option><option value="box">box</option><option value="kg">kg</option><option value="liter">liter</option></select></td>
                        <td><button type="button" class="btn btn-sm btn-danger removePoRow">Hapus</button></td>`;
                }
                // Handler for adding new row manually
                if (addRowButton) {
                    addRowButton.addEventListener('click', function() {
                        addBlankRow();
                    });
                }
                // Delegate remove row
                document.addEventListener('click', function(e) {
                    if (e.target && e.target.classList.contains('removePoRow')) {
                        e.target.closest('tr').remove();
                    }
                });
                // Fetch items when supplier changes (only if not from PR)
                supplierSelect.addEventListener('change', function() {
                    const supplierId = this.value;
                    // Skip if no supplier selected or if this form originates from a PR
                    const hasPR = document.querySelector('input[name="purchase_request_id"]') !== null;
                    if (!supplierId || hasPR) {
                        return;
                    }
                    // Fetch products via AJAX
                    fetch(`{{ url('purchase-orders/get-items') }}/${supplierId}`)
                        .then(response => response.json())
                        .then(data => {
                            // Clear existing rows
                            itemsTableBody.innerHTML = '';
                            if (data.length === 0) {
                                // If supplier has no registered products, start with a blank row
                                addBlankRow();
                                return;
                            }
                            data.forEach((item, index) => {
                                const row = itemsTableBody.insertRow();
                                row.innerHTML = `<td><input type="text" name="items[${index}][product_name]" value="${item.product_name}" class="form-control" required></td>
                                    <td><input type="number" name="items[${index}][quantity]" class="form-control" required></td>
                                    <td><input type="number" step="0.01" name="items[${index}][unit_price]" class="form-control" required></td>
                                    <td>${item.stock}</td>
                                    <td><select name="items[${index}][unit]" class="form-select"><option value="${item.unit}">${item.unit}</option><option value="pcs">pcs</option><option value="box">box</option><option value="kg">kg</option><option value="liter">liter</option></select></td>
                                    <td></td>`;
                            });
                        })
                        .catch(err => {
                            console.error('Gagal memuat barang dari supplier:', err);
                        });
                });
            })();
        </script>
    @endpush
@endsection
<x-layout>
    @section('use_bootstrap', true)
    <x-slot:title>Buat Permintaan Pembelian</x-slot:title>
    
    @push('styles')
        <!-- DataTables CSS -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    @endpush
    
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
                        <h4 class="mb-0">Form Permintaan Pembelian</h4>
                        <a href="{{ route('purchase-requests.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('purchase-requests.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Tanggal Permintaan</label>
                                        <input type="date" name="request_date" class="form-control" 
                                            value="{{ old('request_date', date('Y-m-d')) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Supplier</label>
                                        <select name="supplier_id" id="supplier_id" class="form-select" required>
                                            <option value="">-- Pilih Supplier --</option>
                                            @foreach($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}" 
                                                    {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                    {{ $supplier->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="card mt-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Daftar Item</h5>
                                    <button type="button" id="addItem" class="btn btn-primary" disabled>
                                        <i class="fas fa-plus"></i> Tambah Item
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="itemsTable">
                                            <thead>
                                                <tr>
                                                    <th style="width: 40%">Produk</th>
                                                    <th style="width: 15%">Jumlah</th>
                                                    <th style="width: 15%">Satuan</th>
                                                    <th style="width: 80px">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="no-items">
                                                    <td colspan="5" class="text-center py-3">
                                                        Belum ada item yang ditambahkan
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-4">
                                <label class="form-label">Catatan Tambahan</label>
                                <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan PR
                                </button>
                                <button type="reset" class="btn btn-secondary ms-2">
                                    <i class="fas fa-undo"></i> Reset
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    
    <script>
        // Simpan data produk supplier
        let supplierProducts = [];

        // Event listener untuk perubahan supplier
        document.getElementById('supplier_id').addEventListener('change', async function() {
            const supplierId = this.value;
            const addItemBtn = document.getElementById('addItem');
            
            if (supplierId) {
                try {
                    // Tambahkan CSRF token ke header
                    const response = await fetch(`/procurement/purchase-requests/get-supplier-items/${supplierId}`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });
                    
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    const data = await response.json();
                    console.log('Response data:', data); // Debug
                    
                    if (Array.isArray(data)) {
                        supplierProducts = data;
                        addItemBtn.disabled = false;
                        console.log('Supplier products loaded:', supplierProducts.length, 'items');
                    } else {
                        throw new Error('Invalid data format received');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Gagal mengambil data produk supplier: ' + error.message);
                    supplierProducts = [];
                    addItemBtn.disabled = true;
                }
            } else {
                supplierProducts = [];
                addItemBtn.disabled = true;
            }
        });

        document.getElementById('addItem').addEventListener('click', function() {
            console.log('Add Item button clicked'); // Debug
            console.log('Current supplier products:', supplierProducts); // Debug
            
            if (!supplierProducts || supplierProducts.length === 0) {
                alert('Tidak ada produk supplier yang tersedia. Silakan pilih supplier terlebih dahulu.');
                return;
            }

            const tbody = document.getElementById('itemsTable').getElementsByTagName('tbody')[0];
            const noItemsRow = tbody.querySelector('.no-items');
            if (noItemsRow) {
                noItemsRow.remove();
            }

            const rowCount = tbody.rows.length;
            const newRow = tbody.insertRow();
            
            // Buat opsi untuk produk
            const productOptions = supplierProducts.map(product => 
                `<option value="${product.id}">
                    ${product.product_name}
                </option>`
            ).join('');
            
            console.log('Generated product options:', productOptions); // Debug
            
            const rowHtml = `
                <td>
                    <select name="items[${rowCount}][supplier_product_id]" 
                            class="form-select product-select" required>
                        <option value="">Pilih Produk</option>
                        ${productOptions}
                    </select>
                </td>
                <td>
                    <input type="number" name="items[${rowCount}][quantity]" 
                        class="form-control quantity-input" required min="1" placeholder="0">
                </td>
                <td>
                    <select name="items[${rowCount}][unit]" class="form-select unit-select" required>
                        <option value="">Pilih Unit</option>
                        <option value="pcs">PCS</option>
                        <option value="box">Box</option>
                        <option value="kg">KG</option>
                    </select>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm delete-row">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>`;
                
            console.log('Generated row HTML:', rowHtml); // Debug
            newRow.innerHTML = rowHtml;
        });



        document.addEventListener('click', function(e) {
            if (e.target.closest('.delete-row')) {
                const row = e.target.closest('tr');
                const tbody = row.parentNode;
                row.remove();

                if (tbody.rows.length === 0) {
                    tbody.innerHTML = `
                        <tr class="no-items">
                            <td colspan="5" class="text-center py-3">
                                Belum ada item yang ditambahkan
                            </td>
                        </tr>`;
                }
            }
        });

        document.querySelector('form').addEventListener('submit', function(e) {
            const tbody = document.getElementById('itemsTable').getElementsByTagName('tbody')[0];
            const hasNoItems = tbody.querySelector('.no-items');
            
            if (hasNoItems || tbody.rows.length === 0) {
                e.preventDefault();
                alert('Silakan tambahkan minimal satu item sebelum menyimpan PR');
            }
        });
    </script>
    @endpush
</x-layout>
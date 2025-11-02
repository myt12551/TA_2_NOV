<x-layout>
    <x-slot:title>Buat Permintaan Pembelian</x-slot:title>
    
    <x-slot name="stylesBefore">
        <link href="{{ asset('dist/css/style.min.css') }}" rel="stylesheet">
    </x-slot>

    <x-slot name="styles">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <style>
            .page-wrapper {
                background: #f3f3f9;
                position: relative;
                min-height: 100vh;
                padding: 1.5rem;
            }

            .card {
                background: #fff;
                border: none;
                box-shadow: 0 2px 6px rgba(0,0,0,0.08);
                border-radius: 0.5rem;
                margin-bottom: 1.5rem;
            }

            .card-header {
                background: #fff;
                border-bottom: 1px solid #e9ebec;
                padding: 1rem 1.25rem;
                border-radius: 0.5rem 0.5rem 0 0;
            }

            .card-body {
                padding: 1.25rem;
            }

            .table-responsive {
                border-radius: 0.5rem;
                margin: 0;
            }

            .table {
                margin: 0;
                width: 100%;
            }

            .table > :not(caption) > * > * {
                padding: 0.75rem 1.25rem;
            }

            .table thead {
                background: #f8f9fa;
            }

            .table thead th {
                font-weight: 600;
                border-bottom: 2px solid #e9ebec;
                color: #495057;
            }

            .table tbody td {
                vertical-align: middle;
                border-color: #e9ebec;
            }

            .rounded-2 {
                border-radius: 0.375rem !important;
            }

            .btn {
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
                font-weight: 500;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 0.375rem;
            }

            .btn-primary {
                background: #6458ff;
                border-color: #6458ff;
            }

            .btn-primary:hover {
                background: #5346e8;
                border-color: #5346e8;
            }

            .btn-secondary {
                background: #74788d;
                border-color: #74788d;
            }

            .btn-light {
                background: #f8f9fa;
                border-color: #e9ebec;
                color: #495057;
            }

            .form-control,
            .form-select {
                border-color: #e9ebec;
                padding: 0.5rem 0.75rem;
                border-radius: 0.375rem;
            }

            .form-control:focus,
            .form-select:focus {
                border-color: #6458ff;
                box-shadow: 0 0 0 0.15rem rgba(100,88,255,.25);
            }

            .form-label {
                font-weight: 500;
                margin-bottom: 0.5rem;
                color: #495057;
            }

            .loading-overlay {
                position: absolute;
                inset: 0;
                background: rgba(255,255,255,0.9);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 1000;
                backdrop-filter: blur(2px);
                border-radius: 0.5rem;
            }
            
            .loading-overlay .spinner-border {
                width: 3rem;
                height: 3rem;
                border-width: 0.25rem;
            }

            .text-muted {
                color: #74788d !important;
            }

            .alert-danger {
                background: #fee;
                border-color: #fdd;
                color: #f33;
            }
        </style>
    </x-slot>

    <x-slot name="content">
        <div class="container-fluid py-4">
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
                        <a href="{{ route('procurement.index') }}" class="btn btn-secondary rounded-2">
                            <i class="mdi mdi-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('purchase-requests.store') }}" method="POST">
                            @csrf
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Tanggal Permintaan</label>
                                <input type="date" name="request_date" id="request_date" 
                                    class="form-control rounded-2 @error('request_date') is-invalid @enderror" 
                                    value="{{ old('request_date', date('Y-m-d')) }}" required>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="form-label">Supplier</label>
                                <select name="supplier_id" id="supplier_id" 
                                    class="form-select select2 rounded-2 @error('supplier_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Supplier --</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Keterangan</label>
                                <textarea name="description" id="description" 
                                    class="form-control rounded-2 @error('description') is-invalid @enderror" 
                                    rows="2" placeholder="Tambahkan keterangan jika diperlukan">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>                                <div class="card mt-4">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Daftar Item</h5>
                                        <button type="button" id="addRow" class="btn btn-primary rounded-2">
                                            <i class="mdi mdi-plus me-1"></i> Tambah Item
                                        </button>
                                    </div>

                                    <div class="table-responsive position-relative">
                                        <div id="loadingOverlay" class="loading-overlay d-none">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                        <table class="table" id="itemsTable">
                                            <thead>
                                                <tr>
                                                    <th style="width: 35%">Nama Barang</th>
                                                    <th style="width: 15%">Jumlah</th>
                                                    <th style="width: 15%">Satuan</th>
                                                    <th style="width: 15%">Stok Sekarang</th>
                                                    <th>Catatan</th>
                                                    <th style="width: 80px" class="text-center">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="text-center text-muted">
                                                    <td colspan="6" class="py-3">
                                                        Pilih supplier terlebih dahulu untuk melihat daftar barang
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="card mt-4">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="alert alert-info mb-0">
                                                    <i class="mdi mdi-information-outline me-1"></i>
                                                    Pastikan semua item telah sesuai dengan kebutuhan dan memenuhi jumlah minimum pemesanan.
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="text-end">
                                                    <h6 class="mb-2">Total Estimasi:</h6>
                                                    <h3 id="totalAmount" class="mb-0">Rp 0</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary rounded-2 px-4">
                                        <i class="mdi mdi-content-save me-1"></i> Simpan Permintaan
                                    </button>
                                    <button type="reset" class="btn btn-light border rounded-2 ms-2">
                                        <i class="mdi mdi-refresh me-1"></i> Reset Form
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <x-slot name="scripts">
    <script>
        let isLoading = false;

        function formatNumber(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        function setLoading(loading) {
            isLoading = loading;
            const button = document.getElementById('addRow');
            const overlay = document.getElementById('loadingOverlay');
            
            button.disabled = loading;
            if (loading) {
                button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';
                overlay.classList.remove('d-none');
            } else {
                button.innerHTML = '<i class="mdi mdi-plus me-1"></i> Tambah Item';
                overlay.classList.add('d-none');
            }
        }

        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount);
        }

        // Handler for supplier selection change
        document.getElementById('supplier_id').addEventListener('change', async function() {
            console.log('Supplier selection changed');
            
            const supplierId = this.value;
            const tableBody = document.getElementById('itemsTable').getElementsByTagName('tbody')[0];
            const addRowBtn = document.getElementById('addRow');
            const selectedSupplierName = this.options[this.selectedIndex].text;
            
            // For debugging
            console.log('Selected supplier:', {
                id: supplierId,
                name: selectedSupplierName
            });
            
            // Hide add row button initially
            addRowBtn.style.display = 'none';
            
            // Clear table and show initial message if no supplier selected
            if (!supplierId) {
                tableBody.innerHTML = `
                    <tr class="text-center text-muted">
                        <td colspan="6" class="py-3">
                            Pilih supplier terlebih dahulu untuk melihat daftar barang
                        </td>
                    </tr>`;
                return;
            }
            
            // Show loading state
            // Show loading message in table
            tableBody.innerHTML = `
                <tr class="text-center">
                    <td colspan="6" class="py-3">
                        <div class="d-flex flex-column align-items-center">
                            <div class="spinner-border text-primary mb-2" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <span>Mengambil data produk dari ${selectedSupplierName}...</span>
                        </div>
                    </td>
                </tr>`;

            try {
                setLoading(true); // Show loading state
                console.log('Fetching supplier items...', supplierId);
                
                // Make API request
                const response = await fetch(`/procurement/purchase-requests/get-supplier-items/${supplierId}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                console.log('Response status:', response.status);
                console.log('Response headers:', Object.fromEntries(response.headers));
                
                // For debugging
                console.log('API Response:', {
                    status: response.status,
                    statusText: response.statusText,
                    headers: Object.fromEntries(response.headers.entries())
                });
                
                if (!response.ok) {
                    const errorText = await response.text();
                    console.error('Server error:', errorText);
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                // Parse response JSON
                const result = await response.json();

                if (!result.success) {
                    throw new Error(result.message || 'Gagal memuat data supplier');
                }

                if (!result.data || result.data.length === 0) {
                    tableBody.innerHTML = `
                        <tr class="text-center text-muted">
                            <td colspan="6" class="py-3">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="mdi mdi-package-variant-closed text-muted mb-2" style="font-size: 2rem;"></i>
                                    <p class="mb-1">Tidak ada item tersedia untuk supplier ini.</p>
                                    <small class="text-muted">Silakan hubungi admin untuk menambahkan produk</small>
                                </div>
                            </td>
                        </tr>`;
                    return;
                }

                // Clear existing table content
                tableBody.innerHTML = '';
                
                // Add each product to the table
                result.data.forEach((item, index) => {
                    const row = tableBody.insertRow();
                    row.innerHTML = `
                        <td>
                            <div class="d-flex flex-column">
                                <input type="text" name="items[${index}][product_name]" 
                                    value="${item.name}" class="form-control" required readonly>
                                <small class="text-muted">Kode: ${item.code || '-'}</small>
                                <input type="hidden" name="items[${index}][item_id]" value="${item.id}">
                                <input type="hidden" name="items[${index}][supplier_product_id]" value="${item.supplier_product_id}">
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <input type="number" name="items[${index}][quantity]" 
                                    class="form-control text-end quantity-input" 
                                    min="${item.min_order}" required
                                    placeholder="${item.min_order}"
                                    data-min="${item.min_order}"
                                    data-price="${item.raw_price}"
                                    title="Minimum pemesanan: ${item.min_order} ${item.unit}">
                                <small class="text-muted d-block text-end">Min: ${item.min_order} ${item.unit}</small>
                            </div>
                        </td>
                        <td>
                            <input type="text" name="items[${index}][unit]" 
                                class="form-control text-center" value="${item.unit}" readonly>
                        </td>
                        <td class="text-center">
                            <div class="d-flex flex-column align-items-center">
                                <span class="badge bg-${item.stock > 0 ? 'success' : 'danger'} px-2 py-1">
                                    ${formatNumber(item.stock)} ${item.unit}
                                </span>
                                <input type="hidden" name="items[${index}][current_stock]" value="${item.stock}">
                                <small class="text-muted mt-1">${item.stock > 0 ? 'Tersedia' : 'Stok Habis'}</small>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <input type="text" name="items[${index}][notes]" 
                                    class="form-control" placeholder="Catatan tambahan">
                                <div class="d-flex justify-content-between mt-1">
                                    <small class="text-muted">Harga: Rp ${item.price}</small>
                                    <small class="text-muted">Lead time: ${item.lead_time}</small>
                                </div>
                                <div class="subtotal text-end mt-1 fw-bold d-none">
                                    Subtotal: Rp <span>0</span>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-outline-danger btn-sm removeRow">
                                <i class="mdi mdi-delete"></i>
                            </button>
                        </td>`;
                        
                    // Add event listener for quantity input
                    const quantityInput = row.querySelector('.quantity-input');
                    quantityInput.addEventListener('input', function() {
                        const price = parseFloat(this.dataset.price);
                        const quantity = parseInt(this.value) || 0;
                        const subtotal = price * quantity;
                        const subtotalElement = row.querySelector('.subtotal');
                        subtotalElement.classList.remove('d-none');
                        subtotalElement.querySelector('span').textContent = formatNumber(subtotal);
                        updateTotal();
                    });
                });
                
                // Show add row button after loading data
                addRowBtn.style.display = 'inline-flex';

            } catch (error) {
                console.error('Error:', error);
                let errorMessage = 'Terjadi kesalahan saat memuat data.';
                
                // Try to get more detailed error message
                if (error.response) {
                    try {
                        const errorData = await error.response.json();
                        errorMessage = errorData.message || errorMessage;
                    } catch (e) {
                        console.error('Error parsing error response:', e);
                    }
                }
                
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center py-3">
                            <div class="alert alert-danger mb-0">
                                <i class="mdi mdi-alert-circle me-1"></i> ${errorMessage}
                                <div class="mt-2">
                                    <button type="button" onclick="document.getElementById('supplier_id').dispatchEvent(new Event('change'))" 
                                        class="btn btn-sm btn-outline-danger">
                                        <i class="mdi mdi-refresh me-1"></i> Coba Lagi
                                    </button>
                                    <button type="button" onclick="window.location.reload()" 
                                        class="btn btn-sm btn-outline-secondary ms-2">
                                        <i class="mdi mdi-reload me-1"></i> Muat Ulang Halaman
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>`;
                
                // Log the error to the server
                try {
                    await fetch('{{ route('api.log-error') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            error: error.message,
                            context: {
                                supplier_id: supplierId,
                                url: window.location.href,
                                timestamp: new Date().toISOString()
                            }
                        })
                    });
                } catch (logError) {
                    console.error('Error logging failed:', logError);
                }
            } finally {
                setLoading(false);
            }
        });

        document.getElementById('addRow').addEventListener('click', function() {
            if (isLoading) return;

            const tableBody = document.getElementById('itemsTable').getElementsByTagName('tbody')[0];
            
            if (tableBody.rows.length === 1 && tableBody.rows[0].cells.length === 1) {
                tableBody.innerHTML = '';
            }

            const index = tableBody.rows.length;
            const newRow = tableBody.insertRow();
            newRow.innerHTML = `
                <td>
                    <input type="text" name="items[${index}][product_name]" 
                        class="form-control" required placeholder="Nama produk">
                </td>
                <td>
                    <input type="number" name="items[${index}][quantity]" 
                        class="form-control text-end" min="1" required
                        placeholder="0">
                </td>
                <td>
                    <select name="items[${index}][unit]" class="form-select" required>
                        <option value="">Pilih satuan</option>
                        <option value="pcs">pcs</option>
                        <option value="box">box</option>
                        <option value="kg">kg</option>
                        <option value="liter">liter</option>
                    </select>
                </td>
                <td class="text-center">-</td>
                <td>
                    <input type="text" name="items[${index}][notes]" 
                        class="form-control" placeholder="Optional">
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-outline-danger btn-sm removeRow">
                        <i class="mdi mdi-delete"></i>
                    </button>
                </td>`;
        });

        document.addEventListener('click', function(e) {
            if (e.target && e.target.closest('.removeRow')) {
                const row = e.target.closest('tr');
                const table = document.getElementById('itemsTable');
                row.remove();

                const tbody = table.getElementsByTagName('tbody')[0];
                if (tbody.rows.length === 0) {
                    tbody.innerHTML = `
                        <tr class="text-center text-muted">
                            <td colspan="6" class="py-3">
                                Tidak ada item. Silakan pilih supplier atau tambah item manual.
                            </td>
                        </tr>`;
                }
            }
        });

        // Handle quantity changes and update subtotals
        document.addEventListener('input', function(e) {
            if (e.target && e.target.classList.contains('quantity-input')) {
                const input = e.target;
                const minOrder = parseInt(input.dataset.min);
                const price = parseFloat(input.dataset.price);
                const quantity = parseInt(input.value) || 0;
                const subtotalElement = input.closest('tr').querySelector('.subtotal');
                
                // Validate minimum order
                if (quantity < minOrder) {
                    input.setCustomValidity(`Minimal pemesanan adalah ${minOrder} unit`);
                } else {
                    input.setCustomValidity('');
                }
                
                // Update subtotal
                const subtotal = quantity * price;
                subtotalElement.textContent = `Subtotal: ${formatCurrency(subtotal)}`;
                
                // Update total
                updateTotal();
            }
        });

        // Calculate and update total
        function updateTotal() {
            const inputs = document.querySelectorAll('.quantity-input');
            let total = 0;
            
            inputs.forEach(input => {
                const quantity = parseInt(input.value) || 0;
                const price = parseFloat(input.dataset.price);
                total += quantity * price;
            });
            
            const totalElement = document.getElementById('totalAmount');
            if (totalElement) {
                totalElement.textContent = formatCurrency(total);
            }
        }

        // Form submission validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const tbody = document.getElementById('itemsTable').getElementsByTagName('tbody')[0];
            const hasValidItems = tbody.rows.length > 0 && 
                                !(tbody.rows.length === 1 && tbody.rows[0].cells.length === 1);
            
            if (!hasValidItems) {
                e.preventDefault();
                alert('Silakan tambahkan minimal satu item sebelum menyimpan.');
                return;
            }

            // Validate minimum order quantities
            const quantityInputs = document.querySelectorAll('.quantity-input');
            let hasInvalidQuantity = false;

            quantityInputs.forEach(input => {
                const quantity = parseInt(input.value) || 0;
                const minOrder = parseInt(input.dataset.min);
                
                if (quantity < minOrder) {
                    hasInvalidQuantity = true;
                    input.setCustomValidity(`Minimal pemesanan adalah ${minOrder} unit`);
                }
            });

            if (hasInvalidQuantity) {
                e.preventDefault();
                alert('Beberapa item belum memenuhi jumlah minimal pemesanan. Silakan periksa kembali.');
            }
        });
    </script>
    </x-slot>
</x-layout>

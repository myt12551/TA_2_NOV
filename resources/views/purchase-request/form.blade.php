@extends('layouts.app')

{{-- Gunakan Bootstrap untuk tampilan ini --}}
@section('use_bootstrap', true)

@section('title', 'Buat Permintaan Pembelian')

@section('content')
    {{-- Baris judul dan tombol kembali --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5>Buat Permintaan Pembelian</h5>
        <a href="{{ route('procurement.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('purchase-requests.store') }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="request_date" class="form-label">Tanggal Permintaan</label>
                        <input type="date" name="request_date" id="request_date" class="form-control" required>
                    </div>
                    <div class="col-md-5">
                        <label for="supplier_id" class="form-label">Supplier</label>
                        <select name="supplier_id" id="supplier_id" class="form-select" required>
                            <option value="">-- Pilih Supplier --</option>
                            @foreach(App\Models\Supplier::orderBy('name')->get() as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="description" class="form-label">Keterangan</label>
                        <textarea name="description" id="description" class="form-control" rows="2"></textarea>
                    </div>
                </div>

                <h6>Daftar Item</h6>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="itemsTable">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Barang</th>
                                <th>Jumlah</th>
                                <th>Satuan</th>
                                <th>Stok Sekarang</th>
                                <th>Catatan</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Baris item akan diisi otomatis setelah supplier dipilih -->
                        </tbody>
                    </table>
                </div>
                <div class="mb-3">
                    <button type="button" id="addRow" class="btn btn-sm btn-secondary">Tambah Item Manual</button>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // AJAX: Ambil barang supplier saat supplier dipilih
        document.getElementById('supplier_id').addEventListener('change', function() {
            const supplierId = this.value;
            const tableBody = document.getElementById('itemsTable').getElementsByTagName('tbody')[0];
            tableBody.innerHTML = '';
            if (!supplierId) return;
            fetch(`/purchase-orders/get-items/${supplierId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length === 0) {
                        // Jika supplier tidak punya barang, bisa tambah manual
                        return;
                    }
                    data.forEach((item, index) => {
                        const row = tableBody.insertRow();
                        row.innerHTML = `<td><input type="text" name="items[${index}][product_name]" value="${item.product_name}" class="form-control" required></td>
                            <td><input type="number" name="items[${index}][quantity]" class="form-control" required></td>
                            <td><select name="items[${index}][unit]" class="form-select"><option value="pcs">pcs</option><option value="box">box</option><option value="kg">kg</option><option value="liter">liter</option></select></td>
                            <td>${item.stock}</td>
                            <td><input type="text" name="items[${index}][notes]" class="form-control"></td>
                            <td></td>`;
                    });
                });
        });
        // Tambah item manual
        document.getElementById('addRow').addEventListener('click', function() {
            const tableBody = document.getElementById('itemsTable').getElementsByTagName('tbody')[0];
            const index = tableBody.rows.length;
            const newRow = tableBody.insertRow();
            newRow.innerHTML = `<td><input type="text" name="items[${index}][product_name]" class="form-control" required></td>
                <td><input type="number" name="items[${index}][quantity]" class="form-control" required></td>
                <td><select name="items[${index}][unit]" class="form-select"><option value="pcs">pcs</option><option value="box">box</option><option value="kg">kg</option><option value="liter">liter</option></select></td>
                <td>-</td>
                <td><input type="text" name="items[${index}][notes]" class="form-control"></td>
                <td><button type="button" class="btn btn-sm btn-danger removeRow">Hapus</button></td>`;
        });
        // Hapus baris
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('removeRow')) {
                e.target.closest('tr').remove();
            }
        });
    </script>
    @endpush
@endsection

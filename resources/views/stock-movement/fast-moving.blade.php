<x-layout>
    <x-slot:title>Fast Moving Items</x-slot:title>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Daftar Barang Fast Moving</h3>
                    <a href="{{ route('stock-movement.export', ['status' => 'fast']) }}" class="btn btn-success">
                        <i class="fas fa-file-excel mr-1"></i> Export ke Excel
                    </a>
                </div>
            </div>
    
            <div class="card-body">
                <div class="table-responsive">
                    <table id="fastMovingTable" class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px">No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th class="text-center">Stok</th>
                                <th class="text-center">Terjual (30 Hari)</th>
                                <th class="text-center">Rata-rata/Hari</th>
                                <th class="text-center">Estimasi Habis</th>
                                <th>Rekomendasi</th>
                                <th class="text-center" style="width: 100px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($analyses as $index => $item)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $item->code }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->category->name }}</td>
                                    <td class="text-center">{{ $item->stock }}</td>
                                    <td class="text-center">{{ $item->total_sold }}</td>
                                    <td class="text-center">{{ number_format($item->avg_daily_sales, 2) }}</td>
                                    <td class="text-center">
                                        {{ $item->days_until_empty ? $item->days_until_empty . ' hari' : 'Tidak bergerak' }}
                                    </td>
                                    <td>
                                        <span class="text-success">Sarankan Restock</span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-primary btn-sm create-pr" 
                                                data-item-id="{{ $item->id }}"
                                                data-qty="{{ ceil($item->avg_daily_sales * 30) }}">
                                            <i class="fas fa-plus"></i> Buat PR
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Buat Purchase Request -->
<div class="modal fade" id="createPrModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Buat Purchase Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('purchase-requests.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="item_id" id="prItemId">
                    <div class="mb-3">
                        <label class="form-label">Jumlah Order</label>
                        <input type="number" class="form-control" name="quantity" id="prQuantity" min="1" required>
                        <small class="text-muted">Jumlah yang disarankan berdasarkan rata-rata penjualan 30 hari</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea class="form-control" name="notes" rows="3" placeholder="Tambahkan catatan jika diperlukan"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Buat PR</button>
                </div>
            </form>
        </div>
    </div>
</div>
</x-layout>

@push('scripts')
<script>
$(function () {
    $('#fastMovingTable').DataTable({
        "responsive": true,
        "autoWidth": false,
        "pageLength": 10,
        "order": [[6, 'desc']],
        "language": {
            "lengthMenu": "Tampilkan _MENU_ data per halaman",
            "zeroRecords": "Data tidak ditemukan",
            "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
            "infoEmpty": "Tidak ada data tersedia",
            "infoFiltered": "(difilter dari total _MAX_ data)",
            "search": "Cari:",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Selanjutnya",
                "previous": "Sebelumnya"
            }
        }
    });

    // Handle Create PR button click
    $('.create-pr').click(function() {
        $('#prItemId').val($(this).data('item-id'));
        $('#prQuantity').val($(this).data('qty'));
        $('#createPrModal').modal('show');
    });
});
</script>
@endpush
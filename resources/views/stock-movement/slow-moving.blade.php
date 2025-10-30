<x-layout>
    <x-slot:title>Analisis Barang Pergerakan Lambat</x-slot:title>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Daftar Barang Pergerakan Lambat</h3>
                        <div>
                            <a href="{{ route('stock-movement.index') }}" class="btn btn-secondary me-2">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <a href="{{ route('stock-movement.export', ['status' => 'slow']) }}" class="btn btn-success">
                                <i class="fas fa-file-excel"></i> Export Data
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="slowMovingTable" class="table table-hover table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 50px">No</th>
                                    <th>Kode</th>
                                    <th>Nama Barang</th>
                                    <th class="text-center">Stok</th>
                                    <th class="text-center">Terjual</th>
                                    <th class="text-center">Rata-rata</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center" style="width: 100px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($analyses as $index => $item)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ $item->code }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td class="text-center">{{ $item->stock }}</td>
                                        <td class="text-center">{{ $item->total_sold }} unit</td>
                                        <td class="text-center">{{ number_format($item->avg_daily_sales, 1) }}/hari</td>
                                        <td class="text-center">
                                            <span class="badge bg-danger">Pergerakan Lambat</span>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-warning btn-sm show-recommendation" 
                                                    title="Lihat Rekomendasi"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-placement="top"
                                                    data-bs-html="true"
                                                    data-bs-content="Rekomendasi untuk barang slow-moving:<br>
                                                    - Pertimbangkan pemberian diskon<br>
                                                    - Atur ulang posisi display<br>
                                                    - Evaluasi harga jual<br>
                                                    - Pertimbangkan bundling dengan produk fast-moving">
                                                <i class="fas fa-lightbulb"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada barang dengan pergerakan lambat</td>
                                    </tr>
                                @endforelse
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recommendations Modal -->
<div class="modal fade" id="recommendationModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-lightbulb text-warning me-2"></i>
                    Rekomendasi Penanganan Slow Moving
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Berikut adalah rekomendasi untuk menangani barang slow moving:
                </div>

                <div class="list-group">
                    <div class="list-group-item">
                        <h6 class="mb-1"><i class="fas fa-tag text-warning me-2"></i>Strategi Harga</h6>
                        <p class="mb-1">Evaluasi dan pertimbangkan penyesuaian harga jual untuk meningkatkan daya saing.</p>
                    </div>
                    <div class="list-group-item">
                        <h6 class="mb-1"><i class="fas fa-box text-primary me-2"></i>Visual Merchandising</h6>
                        <p class="mb-1">Atur ulang posisi display produk ke lokasi yang lebih strategis.</p>
                    </div>
                    <div class="list-group-item">
                        <h6 class="mb-1"><i class="fas fa-layer-group text-success me-2"></i>Bundling Produk</h6>
                        <p class="mb-1">Pertimbangkan untuk menggabungkan dengan produk fast-moving dalam paket penjualan.</p>
                    </div>
                    <div class="list-group-item">
                        <h6 class="mb-1"><i class="fas fa-chart-line text-info me-2"></i>Analisis Pasar</h6>
                        <p class="mb-1">Lakukan survei pelanggan untuk memahami penyebab penjualan lambat.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                    <i class="fas fa-check me-1"></i> Mengerti
                </button>
            </div>
        </div>
    </div>
</div>
</x-layout>

@push('scripts')
<script>
$(function () {
    // Initialize DataTable with custom settings
    $('#slowMovingTable').DataTable({
        responsive: true,
        autoWidth: false,
        pageLength: 10,
        order: [[5, 'asc']], // Sort by average daily sales ascending
        dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        language: {
            lengthMenu: "Tampilkan _MENU_ data",
            zeroRecords: "Data tidak ditemukan",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data tersedia",
            infoFiltered: "(difilter dari total _MAX_ data)",
            search: "Pencarian:",
            paginate: {
                first: "<<",
                previous: "<",
                next: ">",
                last: ">>"
            }
        }
    });

    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();

    // Initialize popovers and handle recommendation button click
    $('.show-recommendation').click(function() {
        $('#recommendationModal').modal('show');
    });
    
    // Initialize Bootstrap popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Tooltip(popoverTriggerEl);
    });
});
</script>
@endpush
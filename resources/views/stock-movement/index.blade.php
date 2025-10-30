<x-layout>
    <x-slot:title>Analisis Pergerakan Barang</x-slot:title>
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">
                            <i class="fas fa-chart-line me-2"></i>
                            Analisis Pergerakan Barang
                        </h3>
                        <div class="d-flex gap-2">
                            <a href="{{ route('stock-movement.analyze') }}" class="btn btn-primary">
                                <i class="fas fa-sync-alt me-1"></i> Update
                            </a>
                            <a href="{{ route('stock-movement.export', ['status' => $selectedStatus]) }}" class="btn btn-success">
                                <i class="fas fa-file-excel me-1"></i> Export
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <ul class="nav nav-pills">
                                <li class="nav-item">
                                    <a class="nav-link {{ $selectedStatus == 'all' ? 'active bg-primary' : '' }}"
                                       href="{{ route('stock-movement.index', ['status' => 'all']) }}">
                                        <i class="fas fa-box me-1"></i> Semua
                                    </a>
                                </li>
                                <li class="nav-item ms-2">
                                    <a class="nav-link {{ $selectedStatus == 'fast' ? 'active bg-success' : '' }}"
                                       href="{{ route('stock-movement.index', ['status' => 'fast']) }}">
                                        <i class="fas fa-arrow-trend-up me-1"></i> Fast Moving
                                    </a>
                                </li>
                                <li class="nav-item ms-2">
                                    <a class="nav-link {{ $selectedStatus == 'normal' ? 'active bg-warning' : '' }}"
                                       href="{{ route('stock-movement.index', ['status' => 'normal']) }}">
                                        <i class="fas fa-equals me-1"></i> Normal
                                    </a>
                                </li>
                                <li class="nav-item ms-2">
                                    <a class="nav-link {{ $selectedStatus == 'slow' ? 'active bg-danger' : '' }}"
                                       href="{{ route('stock-movement.index', ['status' => 'slow']) }}">
                                        <i class="fas fa-arrow-trend-down me-1"></i> Slow Moving
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

                    <div class="table-responsive">
                        <table id="stockMovementTable" class="table table-hover table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 50px">#</th>
                                    <th>Nama Barang</th>
                                    <th class="text-center">Stok</th>
                                    <th class="text-center" style="width: 120px">Terjual</th>
                                    <th class="text-center" style="width: 120px">Rata-rata</th>
                                    <th class="text-center" style="width: 120px">Status</th>
                                    <th class="text-center" style="width: 120px">Estimasi</th>
                                    <th class="text-center" style="width: 120px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($analyses as $index => $analysis)
                                    <tr>
                                        <td class="text-center align-middle">{{ $index + 1 }}</td>
                                        <td class="align-middle">
                                            <strong>{{ $analysis->name }}</strong><br>
                                            <small class="text-muted">{{ $analysis->code }} - {{ $analysis->category->name }}</small>
                                        </td>
                                        <td class="text-center align-middle">{{ $analysis->stock }}</td>
                                        <td class="text-center align-middle">{{ $analysis->total_sold }} unit</td>
                                        <td class="text-center align-middle">{{ number_format($analysis->avg_daily_sales, 1) }}/hari</td>
                                        <td class="text-center align-middle">
                                            @php
                                                $statusClass = match($analysis->movement_status) {
                                                    'FAST' => 'success',
                                                    'SLOW' => 'danger',
                                                    default => 'warning'
                                                };
                                                $statusText = match($analysis->movement_status) {
                                                    'FAST' => 'Cepat',
                                                    'SLOW' => 'Lambat',
                                                    default => 'Normal'
                                                };
                                            @endphp
                                            <span class="badge bg-{{ $statusClass }}">{{ $statusText }}</span>
                                        </td>
                                        <td class="text-center align-middle">
                                            @if($analysis->days_until_empty)
                                                <span class="badge bg-info">{{ $analysis->days_until_empty }} hari</span>
                                            @else
                                                <span class="badge bg-secondary">Tidak bergerak</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            @if($analysis->movement_status === 'FAST')
                                                <button class="btn btn-sm btn-success create-pr" 
                                                        title="Buat Purchase Request"
                                                        data-item-id="{{ $analysis->id }}"
                                                        data-item-name="{{ $analysis->name }}"
                                                        data-stock="{{ $analysis->stock }}">
                                                    <i class="fas fa-cart-plus"></i>
                                                </button>
                                            @elseif($analysis->movement_status === 'SLOW')
                                                <button class="btn btn-sm btn-warning show-recommendation"
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
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-3">
                                            <i class="fas fa-box-open me-2"></i> Tidak ada data pergerakan barang
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
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Purchase Request -->
<div class="modal fade" id="createPrModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-cart-plus text-success me-2"></i>
                    Buat Purchase Request
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="prForm" method="POST" action="{{ route('purchase-requests.store') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="item_id" id="prItemId">
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Barang ini terdeteksi sebagai fast moving. Disarankan untuk melakukan restock.
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label class="form-label">Nama Barang</label>
                            <input type="text" class="form-control" id="prItemName" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Stok</label>
                            <input type="number" class="form-control" id="prItemStock" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah Order</label>
                        <input type="number" class="form-control" name="quantity" required min="1">
                        <small class="text-muted">Masukkan jumlah yang ingin dipesan</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea class="form-control" name="notes" rows="2" 
                                  placeholder="Tambahkan catatan jika diperlukan"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan PR
                    </button>
                </div>
            </form>
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
    // Initialize DataTable
    $('#stockMovementTable').DataTable({
        responsive: true,
        autoWidth: false,
        pageLength: 10,
        order: [[4, 'desc']], // Sort by average sales
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

    // Handle Create PR button click
    $('.create-pr').click(function() {
        const btn = $(this);
        $('#prItemId').val(btn.data('item-id'));
        $('#prItemName').val(btn.data('item-name'));
        $('#prItemStock').val(btn.data('stock'));
        $('#createPrModal').modal('show');
    });

    // Initialize popovers and handle recommendation button click
    $('.show-recommendation').click(function() {
        $('#recommendationModal').modal('show');
    });
    
    // Initialize Bootstrap popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Tooltip(popoverTriggerEl);
    });

    $('#prForm').submit(function(e) {
        const quantity = $('input[name="quantity"]').val();
        if(quantity < 1) {
            e.preventDefault();
            alert('Jumlah order harus lebih dari 0');
            return false;
        }
    });
});
</script>
@endpush
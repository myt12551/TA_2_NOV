@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Slow Moving Items</h1>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-arrow-down me-1"></i>
            Barang Pergerakan Lambat
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="slowMovingTable">
                    <thead class="table-light">
                        <tr>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th>Hari Tidak Bergerak</th>
                            <th>Nilai Stok Menganggur</th>
                            <th>Rata-rata/Hari</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($analyses as $analysis)
                        <tr>
                            <td>{{ $analysis->item->code }}</td>
                            <td>{{ $analysis->item->name }}</td>
                            <td>{{ $analysis->item->category->name }}</td>
                            <td>{{ $analysis->current_stock }}</td>
                            <td>{{ $analysis->non_moving_days }}</td>
                            <td>Rp {{ number_format($analysis->stuck_stock_value, 0, ',', '.') }}</td>
                            <td>{{ number_format($analysis->avg_daily_sales, 2) }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm create-promo" 
                                        data-item-id="{{ $analysis->item_id }}"
                                        data-item-name="{{ $analysis->item->name }}">
                                    <i class="fas fa-tag"></i> Set Promo
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Set Promo -->
<div class="modal fade" id="setPromoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Set Promo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('promotions.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="item_id" id="promoItemId">
                    <div class="mb-3">
                        <label class="form-label">Tipe Promo</label>
                        <select class="form-select" name="promo_type" required>
                            <option value="discount">Diskon (%)</option>
                            <option value="special_price">Harga Spesial</option>
                            <option value="bundle">Bundling</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nilai</label>
                        <input type="number" class="form-control" name="promo_value" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Periode Promo</label>
                        <div class="input-group">
                            <input type="date" class="form-control" name="start_date" required>
                            <span class="input-group-text">s/d</span>
                            <input type="date" class="form-control" name="end_date" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea class="form-control" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#slowMovingTable').DataTable({
            order: [[4, 'desc']],
            pageLength: 25
        });

        $('.create-promo').click(function() {
            $('#promoItemId').val($(this).data('item-id'));
            $('#setPromoModal .modal-title').text('Set Promo - ' + $(this).data('item-name'));
            $('#setPromoModal').modal('show');
        });
    });
</script>
@endpush
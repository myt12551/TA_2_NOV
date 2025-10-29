@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Fast Moving Items</h1>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-arrow-up me-1"></i>
            Barang Pergerakan Cepat
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="fastMovingTable">
                    <thead class="table-light">
                        <tr>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Stok Saat Ini</th>
                            <th>Rata-rata/Hari</th>
                            <th>Estimasi Habis</th>
                            <th>Saran Restock</th>
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
                            <td>{{ number_format($analysis->avg_daily_sales, 2) }}</td>
                            <td>{{ $analysis->days_until_empty ?? 'Tidak bergerak' }} hari</td>
                            <td>{{ $analysis->suggested_reorder_qty }} unit</td>
                            <td>
                                <button class="btn btn-primary btn-sm create-pr" 
                                        data-item-id="{{ $analysis->item_id }}"
                                        data-qty="{{ $analysis->suggested_reorder_qty }}">
                                    <i class="fas fa-plus"></i> Buat PR
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

<!-- Modal Buat PR -->
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
                        <label class="form-label">Jumlah</label>
                        <input type="number" class="form-control" name="quantity" id="prQuantity" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea class="form-control" name="notes" rows="3"></textarea>
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
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#fastMovingTable').DataTable({
            order: [[4, 'desc']],
            pageLength: 25
        });

        $('.create-pr').click(function() {
            $('#prItemId').val($(this).data('item-id'));
            $('#prQuantity').val($(this).data('qty'));
            $('#createPrModal').modal('show');
        });
    });
</script>
@endpush
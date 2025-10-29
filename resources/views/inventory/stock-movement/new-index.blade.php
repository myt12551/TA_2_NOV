<x-layout>
  <x-slot:title>Analisis Pergerakan Barang</x-slot:title>

  <div class="row">
    <div class="col-md-12">
      @if(session('success'))
        <x-alert type="success" :message="session('success')"></x-alert>
      @endif
      @if(session('error'))
        <x-alert type="danger" :message="session('error')"></x-alert>
      @endif

      <div class="card">
        <div class="card-header">
          <select id="statusFilter" class="form-select form-select-sm d-inline-block" style="width: 150px;">
            <option value="all" {{ $selectedStatus == 'all' ? 'selected' : '' }}>Semua Status</option>
            <option value="fast" {{ $selectedStatus == 'fast' ? 'selected' : '' }}>Fast Moving</option>
            <option value="normal" {{ $selectedStatus == 'normal' ? 'selected' : '' }}>Normal</option>
            <option value="slow" {{ $selectedStatus == 'slow' ? 'selected' : '' }}>Slow Moving</option>
          </select>
          <div class="float-end">
            <a href="{{ route('inventory.stock-movement.analyze') }}" class="btn btn-info rounded-2">
              <i class="mdi mdi-sync"></i> Update Analisis
            </a>
            <a href="{{ route('inventory.stock-movement.export') }}" class="btn btn-success rounded-2 ms-2">
              <i class="mdi mdi-file-excel"></i> Export Excel
            </a>
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped" id="stockMovementTable">
              <thead>
                <tr>
                  <th class="text-center">Kode</th>
                  <th>Nama Barang</th>
                  <th>Kategori</th>
                  <th class="text-center">Stok</th>
                  <th class="text-center">Terjual/30 Hari</th>
                  <th class="text-center">Status</th>
                  <th class="text-center">Estimasi Habis</th>
                  <th class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($analyses as $analysis)
                <tr>
                  <td class="text-center">{{ $analysis->code }}</td>
                  <td>{{ $analysis->name }}</td>
                  <td>{{ $analysis->category->name }}</td>
                  <td class="text-center">{{ $analysis->stock }}</td>
                  <td class="text-center">{{ number_format($analysis->total_sold, 0) }}</td>
                  <td class="text-center">
                    @if($analysis->movement_status === 'FAST')
                      <span class="badge bg-success">Fast Moving</span>
                    @elseif($analysis->movement_status === 'SLOW')
                      <span class="badge bg-danger">Slow Moving</span>
                    @else
                      <span class="badge bg-warning text-dark">Normal</span>
                    @endif
                  </td>
                  <td class="text-center">
                    @if($analysis->days_until_empty === null)
                      <span class="text-muted">Tidak bergerak</span>
                    @elseif($analysis->days_until_empty <= 7)
                      <span class="text-danger fw-bold">{{ $analysis->days_until_empty }} hari</span>
                    @elseif($analysis->days_until_empty <= 14)
                      <span class="text-warning fw-bold">{{ $analysis->days_until_empty }} hari</span>
                    @else
                      <span class="text-success">{{ $analysis->days_until_empty }} hari</span>
                    @endif
                  </td>
                  <td class="text-center">
                    @if($analysis->days_until_empty !== null && $analysis->days_until_empty <= 7)
                      <a href="{{ route('purchase-orders.create') }}" class="btn btn-sm btn-warning">
                        <i class="mdi mdi-cart"></i> Buat PO
                      </a>
                    @else
                      <span class="text-muted">{{ $analysis->recommendation }}</span>
                    @endif
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-layout>

@push('scripts')
<script>
  $(document).ready(function() {
    $('#stockMovementTable').DataTable({
      language: datatableLanguageOptions,
      order: [[4, 'desc']],
      pageLength: 25,
      columnDefs: [
        { targets: [5,6,7], orderable: false }
      ]
    });

    $('#statusFilter').change(function() {
      window.location.href = "{{ route('inventory.stock-movement.index') }}?status=" + $(this).val();
    });

    // Fokus ke search box saat halaman dimuat
    $('input[type="search"]').focus();
  });
</script>
@endpush
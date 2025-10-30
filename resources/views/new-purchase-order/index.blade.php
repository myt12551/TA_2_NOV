<x-layout>
  <x-slot:title>Daftar Purchase Order</x-slot:title>

  <div class="row">
    <div class="col-md-12">
      @if (session('status'))
        <x-alert type="success" :message="session('status')"></x-alert>
      @endif

      <div class="mb-3 d-flex justify-content-between align-items-center">
        <div>
          @if(auth()->user()->hasRole(['admin', 'warehouse']))
            <a href="{{ route('purchase-requests.create') }}" class="btn btn-success">
              <i class="fas fa-plus"></i> Buat Purchase Request
            </a>
          @endif
        </div>
        <div class="d-flex gap-2">
          <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
            <i class="fas fa-filter"></i> Filter
          </button>
          <x-export-button class="btn-outline-primary"></x-export-button>
        </div>
      </div>

      <div class="collapse mb-3" id="filterCollapse">
        <div class="card card-body">
          <form id="filterForm" class="row g-3">
            <div class="col-md-4">
              <label class="form-label">Status</label>
              <select class="form-select" name="status">
                <option value="">Semua Status</option>
                <option value="draft">Draft</option>
                <option value="sent">Terkirim</option>
                <option value="confirmed">Dikonfirmasi</option>
                <option value="received">Diterima</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Tanggal Mulai</label>
              <input type="date" class="form-control" name="start_date">
            </div>
            <div class="col-md-4">
              <label class="form-label">Tanggal Akhir</label>
              <input type="date" class="form-control" name="end_date">
            </div>
          </form>
        </div>
      </div>

      <!-- Ongoing POs Section -->
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center gap-2">
            <h5 class="card-title mb-0">PO dalam Proses</h5>
            <span class="badge bg-primary" id="poCount">{{ count($ongoingPOs) }}</span>
          </div>
        </div>

        <div class="card-body">
          <div class="table-responsive">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <div class="d-flex align-items-center gap-2">
                <label>Show</label>
                <select class="form-select form-select-sm w-auto" id="poPageLength">
                  <option value="10">10</option>
                  <option value="25">25</option>
                  <option value="50">50</option>
                  <option value="100">100</option>
                </select>
                <label>entries</label>
              </div>
              <div class="d-flex align-items-center">
                <label class="me-2">Search:</label>
                <input type="search" class="form-control form-control-sm" id="poSearch">
              </div>
            </div>
            
            <div class="table-responsive">
            <table class="table table-hover table-striped" id="ongoing_po_table">
              <thead>
                <tr>
                  <th>No</th>
                  <th>PO Number</th>
                  <th>Supplier</th>
                  <th>Tanggal PO</th>
                  <th>Status</th>
                  <th>Total</th>
                  <th width="120px">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($ongoingPOs as $index => $po)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $po->po_number }}</td>
                  <td>{{ optional($po->supplier)->name ?? '-' }}</td>
                  <td>{{ optional($po->po_date)->format('d/m/Y') ?: '-' }}</td>
                  <td>
                    @php
                      $statusClass = match($po->status) {
                        'draft' => 'bg-secondary',
                        'sent' => 'bg-info',
                        'confirmed' => 'bg-success',
                        'received' => 'bg-primary',
                        default => 'bg-secondary'
                      };
                    @endphp
                    <span class="badge {{ $statusClass }}">{{ ucfirst($po->status) }}</span>
                  </td>
                  <td>Rp {{ number_format($po->total_amount ?? 0, 0, ',', '.') }}</td>
                  <td>
                    <a href="{{ route('new-purchase-orders.show', $po->id) }}" 
                       class="btn btn-primary btn-sm">Detail</a>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="7" class="text-center py-3">Tidak ada PO dalam proses</td>
                </tr>
                @endforelse
              </tbody>
            </table>
        </div>
      </div>

      <!-- Approved PRs Section -->
      <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center gap-2">
            <h5 class="card-title mb-0">PR yang Siap Diproses</h5>
            <span class="badge bg-success" id="prCount">{{ count($approvedPRs) }}</span>
          </div>
        </div>

        <div class="card-body">
          <div class="table-responsive">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <div class="d-flex align-items-center gap-2">
                <label>Show</label>
                <select class="form-select form-select-sm w-auto" id="prPageLength">
                  <option value="10">10</option>
                  <option value="25">25</option>
                  <option value="50">50</option>
                  <option value="100">100</option>
                </select>
                <label>entries</label>
              </div>
              <div class="d-flex align-items-center">
                <label class="me-2">Search:</label>
                <input type="search" class="form-control form-control-sm" id="prSearch">
              </div>
            </div>
            <table class="table table-hover table-striped" id="approved_pr_table">
              <thead>
                <tr>
                  <th>PR Number</th>
                  <th>Tanggal Request</th>
                  <th>Pemohon</th>
                  <th>Status</th>
                  <th>Disetujui Oleh</th>
                  <th>Items</th>
                  <th width="150px">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($approvedPRs as $pr)
                <tr>
                  <td>{{ $pr->pr_number }}</td>
                  <td>{{ optional($pr->request_date)->format('d/m/Y') ?: '-' }}</td>
                  <td>{{ optional($pr->requester)->name ?: '-' }}</td>
                  <td>
                    @php
                      $statusClass = match($pr->status) {
                        'pending' => 'bg-warning',
                        'approved' => 'bg-success',
                        'rejected' => 'bg-danger',
                        default => 'bg-secondary'
                      };
                    @endphp
                    <span class="badge {{ $statusClass }}">{{ ucfirst($pr->status) }}</span>
                  </td>
                  <td>{{ optional($pr->approver)->name ?: '-' }}</td>
                  <td>
                    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#prDetailModal{{ $pr->id }}">
                      {{ $pr->items->count() }} items
                    </button>
                  </td>
                  <td>
                    @if($pr->status === 'pending' || $pr->status === null)
                      @if(auth()->user()->role === 'supervisor')
                        <div class="d-flex gap-1">
                          <button type="button" 
                                  class="btn btn-success btn-sm" 
                                  data-bs-toggle="modal" 
                                  data-bs-target="#approvalModal{{ $pr->id }}">
                            Setujui PR
                          </button>
                          <button type="button" 
                                  class="btn btn-danger btn-sm" 
                                  data-bs-toggle="modal" 
                                  data-bs-target="#rejectModal{{ $pr->id }}">
                            Tolak PR
                          </button>
                        </div>
                      @else
                        <span class="badge bg-warning text-dark">
                          <i class="fas fa-clock"></i> Menunggu Persetujuan Supervisor
                        </span>
                      @endif
                      
                      <button type="button"
                              class="btn btn-info btn-sm ms-1"
                              data-bs-toggle="modal"
                              data-bs-target="#prDetailModal{{ $pr->id }}">
                        Lihat Detail
                      </button>
                    @elseif($pr->status === 'approved')
                      <div class="d-flex gap-1">
                        @if($pr->is_validated && auth()->user()->hasRole(['admin', 'supervisor', 'warehouse']))
                          <a href="{{ route('new-purchase-orders.create', $pr->id) }}" 
                             class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                            <i class="fas fa-file-alt"></i>
                            <span>Buat PO</span>
                          </a>
                        @endif
                        
                        @if($pr->validation_document_path)
                          <a href="{{ asset('storage/' . $pr->validation_document_path) }}" 
                             class="btn btn-info btn-sm"
                             target="_blank"
                             title="Lihat Dokumen Validasi">
                            <i class="fas fa-file-pdf"></i>
                          </a>
                        @endif
                        
                        <button type="button"
                                class="btn btn-info btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#prDetailModal{{ $pr->id }}"
                                title="Lihat Detail">
                          <i class="fas fa-eye"></i>
                        </button>
                      </div>
                    @elseif($pr->status === 'rejected')
                      <div class="d-flex gap-1">
                        <span class="badge bg-danger">
                          <i class="fas fa-times-circle"></i> Ditolak
                        </span>
                        <button type="button"
                                class="btn btn-info btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#prDetailModal{{ $pr->id }}"
                                title="Lihat Detail">
                          <i class="fas fa-eye"></i>
                        </button>
                      </div>
                    @endif

                    <!-- Approval Modal -->
                    <div class="modal fade" id="approvalModal{{ $pr->id }}" tabindex="-1" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <form action="{{ route('purchase-requests.approve', $pr->id) }}" 
                                method="POST" 
                                enctype="multipart/form-data">
                            @csrf
                            <div class="modal-header">
                              <h5 class="modal-title">Validasi & Setujui PR</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                              <div class="mb-3">
                                <label class="form-label">Upload Dokumen Validasi</label>
                                <input type="file" 
                                       class="form-control" 
                                       name="validation_document" 
                                       accept=".pdf,.jpg,.jpeg,.png" 
                                       required>
                                <small class="text-muted">
                                  Upload file validasi/persetujuan yang sudah ditandatangani (PDF/Gambar)
                                </small>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                              <button type="submit" class="btn btn-success">Validasi & Setujui</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>

                    <!-- Reject Modal -->
                    <div class="modal fade" id="rejectModal{{ $pr->id }}" tabindex="-1" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <form action="{{ route('purchase-requests.reject', $pr->id) }}" method="POST">
                            @csrf
                            <div class="modal-header">
                              <h5 class="modal-title">Tolak PR</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                              <div class="mb-3">
                                <label class="form-label">Alasan Penolakan</label>
                                <textarea class="form-control" 
                                          name="rejection_reason" 
                                          rows="3" 
                                          required></textarea>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                              <button type="submit" class="btn btn-danger">Tolak PR</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>

                <!-- PR Detail Modal -->
                <div class="modal fade" id="prDetailModal{{ $pr->id }}" tabindex="-1" aria-labelledby="prDetailModalLabel{{ $pr->id }}" aria-hidden="true" data-bs-backdrop="static">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header bg-light">
                        <h5 class="modal-title" id="prDetailModalLabel{{ $pr->id }}">
                          <i class="fas fa-file-alt me-2"></i>Detail PR: {{ $pr->pr_number }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <!-- PR Info -->
                        <div class="row mb-4">
                          <div class="col-md-6">
                            <h6 class="mb-3">Informasi PR</h6>
                            <table class="table table-sm">
                              <tr>
                                <td width="140px">Nomor PR</td>
                                <td>: {{ $pr->pr_number }}</td>
                              </tr>
                              <tr>
                                <td>Tanggal Request</td>
                                <td>: {{ optional($pr->request_date)->format('d/m/Y') }}</td>
                              </tr>
                              <tr>
                                <td>Pemohon</td>
                                <td>: {{ optional($pr->requester)->name }}</td>
                              </tr>
                            </table>
                          </div>
                          <div class="col-md-6">
                            <h6 class="mb-3">Status PR</h6>
                            <table class="table table-sm">
                              <tr>
                                <td width="140px">Status</td>
                                <td>: 
                                  @php
                                    $statusClass = match($pr->status) {
                                      'pending' => 'bg-warning text-dark',
                                      'approved' => 'bg-success',
                                      'rejected' => 'bg-danger',
                                      default => 'bg-secondary'
                                    };
                                  @endphp
                                  <span class="badge {{ $statusClass }}">{{ ucfirst($pr->status) }}</span>
                                </td>
                              </tr>
                              @if($pr->approved_by)
                              <tr>
                                <td>Disetujui Oleh</td>
                                <td>: {{ optional($pr->approver)->name }}</td>
                              </tr>
                              <tr>
                                <td>Tanggal Setuju</td>
                                <td>: {{ optional($pr->approved_at)->format('d/m/Y H:i') }}</td>
                              </tr>
                              @endif
                            </table>
                          </div>
                        </div>

                        <!-- PR Items -->
                        <h6 class="mb-3">Daftar Item</h6>
                        <div class="table-responsive">
                          <table class="table table-bordered table-hover">
                            <thead class="table-light">
                              <tr>
                                <th>#</th>
                                <th>Nama Produk</th>
                                <th class="text-center">Jumlah</th>
                                <th>Satuan</th>
                                <th>Catatan</th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach($pr->items as $index => $item)
                              <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $item->product_name }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td>{{ $item->unit }}</td>
                                <td>{{ $item->notes ?: '-' }}</td>
                              </tr>
                              @endforeach
                            </tbody>
                          </table>
                        </div>

                        @if($pr->description)
                        <div class="mt-4">
                          <h6 class="mb-2">Deskripsi PR</h6>
                          <p class="mb-0 bg-light p-3 rounded">{{ $pr->description }}</p>
                        </div>
                        @endif

                        @if($pr->rejection_reason)
                        <div class="mt-4">
                          <h6 class="mb-2">Alasan Penolakan</h6>
                          <p class="mb-0 bg-danger bg-opacity-10 text-danger p-3 rounded">
                            {{ $pr->rejection_reason }}
                          </p>
                        </div>
                        @endif
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                          <i class="fas fa-times me-1"></i>Tutup
                        </button>
                        @if($pr->validation_document_path)
                        <a href="{{ asset('storage/' . $pr->validation_document_path) }}" 
                           class="btn btn-info"
                           target="_blank">
                          <i class="fas fa-file-pdf me-1"></i>Lihat Dokumen Validasi
                        </a>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
                @empty
                <tr>
                  <td colspan="7" class="text-center py-3">Tidak ada PR yang siap diproses</td>
                </tr>
                @endforelse
              </tbody>
            </table>
        </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      // Handle modal close properly
      $('.modal').on('hide.bs.modal', function (e) {
        // Reset form if exists
        const form = $(this).find('form');
        if (form.length) {
          form[0].reset();
        }
        // Remove any error messages
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
      });

      // Close modal on backdrop click
      $('.modal').on('click', function(e) {
        if ($(e.target).hasClass('modal')) {
          $(this).modal('hide');
        }
      });

      // ESC key to close modal
      $(document).on('keydown', function(e) {
        if (e.keyCode === 27) { // ESC key
          $('.modal').modal('hide');
        }
      });

      // Initialize DataTables
      const poTable = $('#ongoing_po_table').DataTable({
        "language": datatableLanguageOptions,
        "order": [[0, 'asc']],
        "pageLength": 10,
        "dom": 'rt<"bottom"ip>',
        "columnDefs": [{
          "targets": [6],
          "orderable": false,
          "searchable": false
        }]
      });

      const prTable = $('#approved_pr_table').DataTable({
        "language": datatableLanguageOptions,
        "pageLength": 10,
        "dom": 'rt<"bottom"ip>',
        "columnDefs": [{
          "targets": [6],
          "orderable": false,
          "searchable": false
        }]
      });

      // Connect custom search boxes
      $('#poSearch').on('keyup', function() {
        poTable.search(this.value).draw();
      });

      $('#prSearch').on('keyup', function() {
        prTable.search(this.value).draw();
      });

      // Connect custom page length selectors
      $('#poPageLength').on('change', function() {
        poTable.page.len(this.value).draw();
      });

      $('#prPageLength').on('change', function() {
        prTable.page.len(this.value).draw();
      });

      // Handle filter form
      $('#filterForm select, #filterForm input').on('change', function() {
        const data = $('#filterForm').serializeArray();
        let filterObj = {};
        data.forEach(item => {
          if (item.value) filterObj[item.name] = item.value;
        });
        
        poTable.columns().every(function() {
          const column = this;
          if (filterObj[column.header().textContent.toLowerCase()]) {
            column.search(filterObj[column.header().textContent.toLowerCase()]).draw();
          }
        });
      });

      // Initialize tooltips
      const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
      tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
      });

      // Focus search on page load
      $('input[type="search"]').first().focus();
    });
  </script>

  <style>
    /* Table Styling */
    .table > :not(caption) > * > * {
      padding: 0.75rem 1rem;
    }
    
    .table-hover tbody tr:hover {
      background-color: rgba(0,0,0,.02);
    }

    .table-sm td {
      padding: 0.3rem;
    }
    
    /* Layout & Spacing */
    .collapse {
      transition: all 0.3s ease;
    }

    .btn-group {
      gap: 0.5rem;
    }

    .modal-lg {
      max-width: 800px;
    }

    /* Status Badges */
    .badge {
      font-size: 0.85em;
      padding: 0.35em 0.65em;
    }

    .badge i {
      margin-right: 0.25rem;
    }

    /* Action Buttons */
    .btn-sm {
      padding: 0.25rem 0.5rem;
      font-size: 0.875rem;
    }

    .btn i {
      font-size: 0.9em;
    }

    /* Card & Modal Styling */
    .card-header {
      background-color: rgba(0,0,0,.03);
      border-bottom: 1px solid rgba(0,0,0,.125);
    }

    .modal-header {
      border-bottom: 1px solid rgba(0,0,0,.1);
    }

    .modal-footer {
      border-top: 1px solid rgba(0,0,0,.1);
    }

    /* Search & Filter Controls */
    .form-select-sm {
      padding: 0.25rem 2rem 0.25rem 0.5rem;
    }

    .form-control-sm {
      padding: 0.25rem 0.5rem;
    }

    /* Info & Description Sections */
    .bg-light {
      background-color: rgba(0,0,0,.02) !important;
    }

    .rounded {
      border-radius: 0.375rem !important;
    }
  </style>
</x-layout>
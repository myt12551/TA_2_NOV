<x-layout>
  <x-slot:title>Daftar Pelanggan</x-slot:title>

  <div class="row">
    <div class="col-md-12">
      @if (session('status'))
        <x-alert type="success" :message="session('status')"></x-alert>
      @endif
      @if (session('error'))
        <x-alert type="error" :message="session('error')"></x-alert>
      @endif
      <div class="card">
        <div class="card-header">
          <x-export-button></x-export-button>
          <a class="btn btn-primary float-end rounded-2" href="{{ route('customer.create') }}" tabindex="1">Tambah
            Pelanggan</a>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            @include('customer.table')
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Detil -->
  <div class="modal fade" id="detail-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Detil Pelanggan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <div class="row align-items-center mt-2">
              <div class="col-md-4">
                <label for="name"><b>Nama Pelanggan</b></label>
              </div>
              <div class="col-md-8">
                <input type="text" class="form-control" id="name" readonly>
              </div>
            </div>
            <div class="row align-items-center mt-2">
              <div class="col-md-4">
                <label for="phone"><b>No. Telp</b></label>
              </div>
              <div class="col-md-8">
                <input type="text" class="form-control" id="phone" readonly>
              </div>
            </div>
            <div class="row align-items-center mt-2">
              <div class="col-md-4">
                <label for="address"><b>Alamat</b></label>
              </div>
              <div class="col-md-8">
                <input type="address" class="form-control" id="address" readonly>
              </div>
            </div>
            <div class="row align-items-center mt-2">
              <div class="col-md-4">
                <label for="transactions"><b>Jumlah Transaksi</b></label>
              </div>
              <div class="col-md-8">
                <input type="text" class="form-control" id="transactions" readonly>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      $('#customer_table').DataTable({
        "language": datatableLanguageOptions,
        "columnDefs": [{
          "targets": [4],
          "orderable": false,
          "searchable": false
        }]
      });

      $('input[type="search"]').focus();

      $(document).on('click', '#detail-btn', function() {
        $('#name').val($(this).data('name'));
        $('#phone').val($(this).data('phone'));
        $('#address').val($(this).data('address'));
        $('#transactions').val($(this).data('transactions'));
      });

      function exportData(type) {
        window.location.href = "/customer/export?type=" + type;
      }
    });
  </script>
</x-layout>

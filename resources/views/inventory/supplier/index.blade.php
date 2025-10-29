<x-layout>
  <x-slot:title>Daftar Supplier</x-slot:title>

  <div class="row">
    <div class="col-md-12">
      @if (session('status'))
        <x-alert type="success" :message="session('status')"></x-alert>
      @endif
      <div class="card">
        <div class="card-header">
          <x-export-button></x-export-button>
          <a class="btn btn-primary float-end rounded-2" href="{{ route('supplier.create') }}" tabindex="1">Tambah
            Supplier</a>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            @include('inventory.supplier.table')
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
          <h5 class="modal-title" id="exampleModalLabel">Detil Supplier</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <div class="row align-items-center mt-2">
              <div class="col-md-4">
                <label for="name"><b>Nama Supplier</b></label>
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
                <input type="tel" class="form-control" id="phone" readonly>
              </div>
            </div>
            <div class="row align-items-center mt-2">
              <div class="col-md-4">
                <label for="email"><b>Email</b></label>
              </div>
              <div class="col-md-8">
                <input type="email" class="form-control" id="email" readonly>
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
                <label for="description"><b>Deskripsi</b></label>
              </div>
              <div class="col-md-8">
                <input type="text" class="form-control" id="description" readonly>
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
      $('#supplier_table').DataTable({
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
        $('#email').val($(this).data('email'));
        $('#address').val($(this).data('address'));
        $('#description').val($(this).data('description'));
      });

      function exportData(type) {
        window.location.href = "/supplier/export?type=" + type;
      }
    });
  </script>
</x-layout>

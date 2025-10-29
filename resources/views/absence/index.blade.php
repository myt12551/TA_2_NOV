<x-layout>
  <x-slot:title>Daftar Absensi</x-slot:title>

  <div class="row">
    <div class="col-md-12">
      @if (session('status'))
        <x-alert type="success" :message="session('status')"></x-alert>
      @endif
      <div class="card">
        <div class="card-header">
          <x-export-button></x-export-button>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            @include('absence.table')
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
          <h5 class="modal-title" id="exampleModalLabel">Detil Absensi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <div class="row align-items-center mt-2">
              <div class="col-md-4">
                <label for="name"><b>Nama Pegawai</b></label>
              </div>
              <div class="col-md-8">
                <input type="text" class="form-control" id="name" readonly>
              </div>
            </div>
            <div class="row align-items-center mt-2">
              <div class="col-md-4">
                <label for="date"><b>Tanggal</b></label>
              </div>
              <div class="col-md-8">
                <input type="text" class="form-control" id="date" readonly>
              </div>
            </div>
            <div class="row align-items-center mt-2">
              <div class="col-md-4">
                <label for="login_at"><b>Jam Masuk</b></label>
              </div>
              <div class="col-md-8">
                <input type="text" class="form-control" id="login_at" readonly>
              </div>
            </div>
            <div class="row align-items-center mt-2">
              <div class="col-md-4">
                <label for="logout_at"><b>Jam Keluar</b></label>
              </div>
              <div class="col-md-8">
                <input type="text" class="form-control" id="logout_at" readonly>
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
      $('#absence_table').DataTable({
        "language": datatableLanguageOptions,
        "columnDefs": [{
          "targets": [5],
          "orderable": false,
          "searchable": false
        }]
      });

      $('input[type="search"]').focus();

      $(document).on('click', '#detail-btn', function() {
        $('#name').val($(this).data('name'));
        $('#login_at').val($(this).data('login-at'));
        $('#logout_at').val($(this).data('logout-at'));
        $('#date').val($(this).data('created-at'));
      });

      function exportData(type) {
        window.location.href = "/absence/export?type=" + type;
      }
    });
  </script>
</x-layout>

<x-layout>
  <x-slot:title>Daftar Pegawai</x-slot:title>

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
          <a class="btn btn-primary float-end rounded-2" href="{{ route('user.create') }}" tabindex="1">Tambah
            Pegawai</a>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            @include('user.table')
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
          <h5 class="modal-title" id="exampleModalLabel">Detil Pegawai</h5>
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
                <label for="username"><b>Username</b></label>
              </div>
              <div class="col-md-8">
                <input type="text" class="form-control" id="username" readonly>
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
                <label for="phone"><b>No. Telp</b></label>
              </div>
              <div class="col-md-8">
                <input type="tel" class="form-control" id="phone" readonly>
              </div>
            </div>
            <div class="row align-items-center mt-2">
              <div class="col-md-4">
                <label for="position"><b>Jabatan</b></label>
              </div>
              <div class="col-md-8">
                <input type="text" class="form-control" id="position" readonly>
              </div>
            </div>
            <div class="row align-items-center mt-2">
              <div class="col-md-4">
                <label for="role"><b>Hak Akses</b></label>
              </div>
              <div class="col-md-8">
                <input type="text" class="form-control" id="role" readonly>
              </div>
            </div>
            <div class="row align-items-center mt-2">
              <div class="col-md-4">
                <label for="picture"><b>Foto</b></label>
              </div>
              <div class="col-md-8">
                <img src="assets/images/users/default.jpg" id="picture" alt="Foto pegawai" width="100">
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
      $('#user_table').DataTable({
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
        $('#username').val($(this).data('username'));
        $('#email').val($(this).data('email'));
        $('#phone').val($(this).data('phone'));
        let role = $(this).data('role');
        if ($(this).data('role') == 'cashier') role = 'Kasir';
        $('#role').val(role.charAt(0).toUpperCase() + role.slice(1));
        $('#position').val($(this).data('position'));
        $('#picture').attr('src', '/assets/images/users/' + $(this).data('picture'));
      });

      function exportData(type) {
        window.location.href = "/user/export?type=" + type;
      }
    });
  </script>
</x-layout>

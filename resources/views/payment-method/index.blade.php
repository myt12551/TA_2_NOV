<x-layout>
  <x-slot:title>Daftar Metode Pembayaran</x-slot:title>


  <div class="row">
    <div class="col-md-12">
      @if (session('status'))
        <x-alert type="success" :message="session('status')"></x-alert>
      @endif
      <div class="card">
        <div class="card-header">
          <x-export-button></x-export-button>
          <a class="btn btn-primary float-end rounded-2" href="{{ route('payment-method.create') }}" tabindex="1">Tambah
            Metode Pembayaran</a>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            @include('payment-method.table')
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      $('#payment_method_table').DataTable({
        "language": datatableLanguageOptions,
        "columnDefs": [{
          "targets": [2],
          "orderable": false,
          "searchable": false
        }]
      });

      $('input[type="search"]').focus();
    });

    function exportData(type) {
      window.location.href = "/payment-method/export?type=" + type;
    }
  </script>
</x-layout>

<x-layout>
  <x-slot:title>Daftar Kategori</x-slot:title>


  <div class="row">
    <div class="col-md-12">
      @if (session('status'))
        <x-alert type="success" :message="session('status')"></x-alert>
      @endif
      <div class="card">
        <div class="card-header">
          <x-export-button></x-export-button>
          <a class="btn btn-primary float-end rounded-2" href="{{ route('category.create') }}" tabindex="1">Tambah
            Kategori</a>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            @include('inventory.category.table')
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      $('#category_table').DataTable({
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
      window.location.href = "/category/export?type=" + type;
    }
  </script>
</x-layout>

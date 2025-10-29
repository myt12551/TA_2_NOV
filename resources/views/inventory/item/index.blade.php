<x-layout>
  <x-slot:title>Daftar Barang</x-slot:title>
  <div class="row">
    <div class="col-md-12">
      @if (session('status'))
        <x-alert type="success" :message="session('status')"></x-alert>
      @endif
      <div class="card">
        <div class="card-header">
          <x-export-button></x-export-button>
          <a class="btn btn-primary float-end rounded-2" href="{{ route('item.create') }}" tabindex="1">Tambah
            Barang</a>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            @include('inventory.item.table')
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Detil -->
  <div class="modal fade" id="detail-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Detil Barang</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="code">Kode Barang</label>
            <div class="input-group">
              <input type="text" class="form-control" id="code" disabled>
            </div>
          </div>
          <div class="form-group">
            <label for="name">Nama Barang</label>
            <input type="text" class="form-control" id="name" disabled>
          </div>
          <label for="category">Kategori Barang</label>
          <div class="form-group">
            <input type="text" class="form-control" disabled id="category">
          </div>

          <div class="form-group">
            <label for="cost_price">Harga Modal</label>
            <input type="number" class="form-control" id="cost-price" disabled>
          </div>
          <div class="form-group">
            <label>Harga Jual</label>
            <input type="number" class="form-control" id="selling-price" disabled>
          </div>
          <div class="form-group d-none" id="wholesale-prices-wrapper">
            <div class="row">
              <div class="col-md-6">
                <label>Harga Grosir</label>
              </div>
              <div class="col-md-6">
                <label>Minimal Pembelian</label>
              </div>
            </div>
            <div id="wholesale-prices"></div>
          </div>
          <div class="form-group">
            <label for="stock">Stok</label>
            <input type="number" class="form-control" id="stock" disabled>
          </div>
          <div class="form-group">
            <label for="img-preview">Foto</label><br>
            <img src="/assets/images/items/default.png" width="100" id="img-preview" alt="Foto" />
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
      $('#items-table').DataTable({
        "language": datatableLanguageOptions,
        "autoWidth": false,
        "columnDefs": [{
          "targets": [6],
          "orderable": false,
          "searchable": false
        }]
      });

      $('input[type="search"]').focus();


      $(document).on('click', '#detail-btn', function() {
        $('#wholesale-prices-wrapper').addClass('d-none');
        $('#wholesale-prices').html('');

        $('#code').val($(this).data('code'));
        $('#name').val($(this).data('name'));
        $('#category').val($(this).data('category'));
        $('#cost-price').val($(this).data('cost-price'));
        $('#selling-price').val($(this).data('selling-price'));
        $('#stock').val($(this).data('stock'));
        $('#picture').attr('src', '/assets/images/users/' + $(this).data('picture'));

        const wholesalePrices = $(this).data('wholesale-prices');
        if (wholesalePrices.length > 0) {
          let element = '';
          wholesalePrices.forEach(wholesalePrice => {
            element += `
              <div class="row mb-2">
                <div class="col-md-6">
                  <input type="number" class="form-control" value="${wholesalePrice.price}" disabled>
                </div>
                <div class="col-md-6">
                  <input type="number" class="form-control" value="${wholesalePrice.min_qty}" disabled>
                </div>
              </div>
            `;
          });
          $('#wholesale-prices').html(element);
          $('#wholesale-prices-wrapper').removeClass('d-none');
        }
      });
    });

    function exportData(type) {
      window.location.href = "/item/export?type=" + type;
    }
  </script>

</x-layout>

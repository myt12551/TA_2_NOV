<table class="table" id="all_items_table">
  <thead>
    <tr>
      <th><b>Kode Barang</b></th>
      <th><b>Nama Barang</b></th>
      <th><b>Harga</b></th>
      <th><b>Stok</b></th>
      <th><b>Aksi</b></th>
    </tr>
  </thead>
  <tbody>
    @if (count($items) < 1)
      <tr>
        <td colspan="5" class="text-center">
          <strong>Tidak ada data</strong>
        </td>
      </tr>
    @endif
    @foreach ($items as $item)
      <tr>
        <td>
          {{ $item->code }}
        </td>
        <td title="{{ $item->name }}">
          {{ strlen($item->name) > 110 ? substr($item->name, 0, 110) . '...' : $item->name }}
        </td>
        <td>
          <span>
            @indo_currency($item->selling_price)
          </span>
        </td>
        <td>
          <span id="item_stock">
            {{ $item->stock }}
          </span>
        </td>
        <td>
          <button class="btn btn-primary btn-xs rounded-circle" onclick="add_to_draft_cart('{{ $item->id }}')">
            <i class="fas fa-check"></i>
          </button>
        </td>
      </tr>
    @endforeach

  </tbody>
</table>

<script>
  $(document).ready(function() {
    $('#all_items_table').DataTable({
      "language": {
        "url": datatableLanguageOptions
      },
      "columnDefs": [{
        "targets": [4],
        "orderable": false,
        "searchable": false
      }]
    });
  });
</script>

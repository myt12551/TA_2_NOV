<table class="table" id="items-table">
  <thead class=" text-primary">
    <tr>
      <th>
        <b>No</b>
      </th>
      <th>
        <b>Nama Barang</b>
      </th>
      <th>
        <b>Kategori</b>
      </th>
      <th>
        <b>Modal</b>
      </th>
      <th>
        <b>Harga</b>
      </th>
      <th>
        <b>Stok</b>
      </th>
      @if ($type != 'export')
        <th>
          <b>Aksi</b>
        </th>
      @endif
    </tr>
  </thead>
  <tbody>
    @foreach ($items as $item)
      <tr>
        <td>
          {{ $loop->iteration }}
        </td>
        <td title="{{ $item->name }}">
          @if ($type != 'export')
            {{ Str::limit($item->name, 40, '...') }}
          @endif

          @if ($type == 'export')
            {{ $item->name }}
          @endif
        </td>
        <td>
          {{ $item->category->name }}
        </td>
        <td>
          @indo_currency($item->cost_price)
        </td>
        <td>

          @indo_currency($item->selling_price)
        </td>
        <td>
          {{ $item->stock }}
        </td>
        @if ($type != 'export')
          <td>
            <button class="btn btn-sm rounded-3 text-white btn-secondary" data-bs-toggle="modal"
              data-bs-target="#detail-modal" data-code="{{ $item->code }}" data-name="{{ $item->name }}"
              data-category="{{ $item->category->name }}" data-picture="{{ $item->picture }}"
              data-stock="{{ $item->stock }}" data-cost-price="{{ $item->cost_price }}"
              data-selling-price="{{ $item->selling_price }}" data-wholesale-prices="{{ $item->wholesalePrices }}"
              id="detail-btn">
              <i class="fas fa-info-circle"></i>
            </button>
            <a href="{{ route('item.edit', $item->id) }}" class="btn btn-sm rounded-3 text-white btn-success">
              <i class="fas fa-edit"></i>
            </a>
            <form action="{{ route('item.destroy', $item->id) }}" method="post" class="d-inline">
              <button type="submit"
                onclick="return confirm('Apakah anda yakin ingin menghapus barang {{ $item->name }}?')"
                class="btn btn-sm rounded-3 text-white btn-danger">
                <i class="fas fa-trash-alt"></i>
              </button>
              @csrf
              @method('DELETE')
            </form>
          </td>
        @endif
      </tr>
    @endforeach
  </tbody>
</table>

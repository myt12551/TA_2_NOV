<table class="table" id="customer_table">
  <thead class="text-primary">
    <tr>
      <th><b>No</b></th>
      <th><b>Nama</b></th>
      <th><b>No. Telp</b></th>
      <th><b>Jumlah Transaksi</b></th>
      @if ($type != 'export')
        <th class="text-center"><b>Aksi</b></th>
      @endif
    </tr>
  </thead>
  <tbody>
    @foreach ($customers as $customer)
      <tr>
        <td style="width: 10%;">{{ $loop->iteration }}</td>
        <td>{{ $customer->name }}</td>
        <td>{{ $customer->phone }}</td>
        <td align="center">{{ count($customer->transactions) }}</td>
        @if ($type != 'export')
          <td class="text-center" style="width: 25%;">
            {{-- <button class="btn btn-sm rounded-3 text-white btn-secondary" data-bs-toggle="modal"
              data-bs-target="#detail-modal" data-name="{{ $customer->name }}"
              data-phone="{{ $customer->phone }}"data-address="{{ $customer->address }}"
              data-transactions="{{ count($customer->transactions) }}" id="detail-btn">
              <i class="fas fa-info-circle"></i> Detil
            </button> --}}
            <a href="{{ route('customer.show', $customer->id) }}" class="btn btn-sm rounded-3 text-white btn-secondary">
              <i class="fas fa-info-circle"></i>
              Detil
            </a>
            <a href="{{ route('customer.edit', $customer->id) }}" class="btn btn-sm rounded-3 text-white btn-success">
              <i class="fas fa-edit"></i>
              Ubah
            </a>
            <form action="{{ route('customer.destroy', $customer->id) }}" method="post" class="d-inline">
              <button type="submit"
                onclick="return confirm('Apakah anda yakin ingin menghapus pelanggan {{ $customer->name }}?')"
                class="btn btn-sm rounded-3 text-white btn-danger">
                <i class="fas fa-trash-alt"></i>
                Hapus
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

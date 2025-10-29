<table class="table" id="supplier_table">
  <thead class="text-primary">
    <tr>
      <th><b>No</b></th>
      <th><b>Nama</b></th>
      <th><b>No. Telp</b></th>
      <th><b>Email</b></th>
      @if ($type != 'export')
        <th class="text-center"><b>Aksi</b></th>
      @endif
    </tr>
  </thead>
  <tbody>
    @foreach ($suppliers as $supplier)
      <tr>
        <td style="width: 10%;">{{ $loop->iteration }}</td>
        <td>{{ $supplier->name }}</td>
        <td>{{ $supplier->phone }}</td>
        <td>{{ $supplier->email }}</td>
        @if ($type != 'export')
          <td style="width: 25%;" class="text-center">
            <button class="btn btn-sm rounded-3 text-white btn-secondary" data-bs-toggle="modal"
              data-bs-target="#detail-modal" data-name="{{ $supplier->name }}" data-phone="{{ $supplier->phone }}"
              data-email="{{ $supplier->email }}" data-address="{{ $supplier->address }}"
              data-description="{{ $supplier->description }}" id="detail-btn">
              <i class="fas fa-info-circle"></i> Detil
            </button>
            <a href="{{ route('supplier.edit', $supplier->id) }}" class="btn btn-sm rounded-3 text-white btn-success">
              <i class="fas fa-edit"></i>
              Ubah
            </a>
            <form action="{{ route('supplier.destroy', $supplier->id) }}" method="post" class="d-inline">
              <button type="submit"
                onclick="return confirm('Apakah anda yakin ingin menghapus supplier {{ $supplier->name }}?')"
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

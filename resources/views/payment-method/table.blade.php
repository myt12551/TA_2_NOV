<table class="table" id="payment_method_table">
  <thead class="text-primary">
    <tr>
      <th><b>No</b></th>
      <th><b>Metode Pembayaran</b></th>
      @if ($type != 'export')
        <th class="text-center"><b>Aksi</b></th>
      @endif
    </tr>
  </thead>
  <tbody>
    @if (count($payment_methods) < 1)
      <tr>
        <td colspan="3" class="text-center">
          <strong>Tidak ada data</strong>
        </td>
      </tr>
    @endif
    @foreach ($payment_methods as $payment_method)
      <tr>
        <td style="width: 10%;">{{ $loop->iteration }}</td>
        <td>{{ $payment_method->name }}</td>
        @if ($type != 'export')
          <td class="text-center">
            <a href="{{ route('payment-method.edit', $payment_method->id) }}" class="btn btn-sm rounded-3 text-white btn-success">
              <i class="fas fa-edit"></i>
              Ubah
            </a>
            <form action="{{ route('payment-method.destroy', $payment_method->id) }}" method="post" class="d-inline">
              <button type="submit"
                onclick="return confirm('Apakah anda yakin ingin menghapus metode pembayaran {{ $payment_method->name }}?')"
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

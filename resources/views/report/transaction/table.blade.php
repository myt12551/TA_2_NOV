<div class="table-responsive">
  <table class="table " id="report-table">
    <thead>
      <tr>
        <th><b>No</b></th>
        <th><b>Faktur</b></th>
        <th><b>Total</b></th>
        <th><b>Metode Pembayaran</b></th>
        @if ($type == 'export')
          <th><b>Diskon</b></th>
          <th><b>Uang</b></th>
          <th><b>Kembalian</b></th>
          <th><b>Status</b></th>
          <th><b>Catatan</b></th>
        @endif
        @if ($type != 'export')
          <th><b>Status</b></th>
        @endif
        <th><b>Tanggal Transaksi</b></th>
        @if ($type != 'export')
          <th style="text-align: center"><b>Aksi</b></th>
        @endif
      </tr>
    </thead>
    <tbody>
      @foreach ($transactions as $transaction)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $transaction->invoice }}</td>
          <td>@indo_currency($transaction->total)</td>
          <td>{{ $transaction->paymentMethod ? $transaction->paymentMethod->name : '-' }}</td>
          @if ($type != 'export')
            <td>{{ $transaction->status == 'paid' ? 'Lunas' : 'Hutang' }}</td>
          @endif
          @if ($type == 'export')
            <td>@indo_currency($transaction->discount)</td>
            <td>{{ $transaction->amount ? indo_currency($transaction->amount) : 0 }}</td>
            <td>@indo_currency($transaction->change)</td>
            <td>{{ $transaction->status == 'paid' ? 'Lunas' : 'Hutang' }}</td>
            <td>{{ $transaction->note ? $transaction->note : '-' }}</td>
          @endif
          <td>{{ $transaction->updated_at->locale('id')->isoFormat('dddd, D MMMM Y') }}</td>
          @if ($type != 'export')
            <td style="text-align: center">
              <button class="btn btn-xs btn-secondary text-white" data-invoice="{{ $transaction->invoice }}"
                data-total="{{ $transaction->total }}"
                data-payment_method="{{ $transaction->paymentMethod ? $transaction->paymentMethod->name : '-' }}"
                data-discount="{{ $transaction->discount }}" data-amount="{{ $transaction->amount }}"
                data-change="{{ $transaction->change }}"
                data-status="{{ $transaction->status == 'paid' ? 'Lunas' : 'Hutang' }}"
                data-note="{{ $transaction->note }}"
                data-updated_at="{{ $transaction->updated_at->locale('id')->isoFormat('dddd, D MMMM Y') }}"
                data-time="{{ $transaction->updated_at->locale('id')->isoFormat('HH:mm:ss') }}"
                data-cashier="{{ $transaction->user->name }}" onclick="showDetail(this)">
                <i class="fas fa-info-circle"></i>
              </button>
              <button id="transaction_detail_modal_btn" class="btn btn-xs btn-primary text-white"
                data-id="{{ $transaction->id }}" onclick="getTransactionDetail(this)">
                <i class="fas fa-shopping-bag"></i>
              </button>
              <form action="{{ route('transaction.destroy', $transaction->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-xs btn-danger text-white"
                  onclick="return confirm('Apakah anda yakin ingin menghapus data penjualan ini?')">
                  <i class="fas fa-trash"></i>
                </button>
              </form>
            </td>
          @endif
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

<script>
  $(document).ready(function() {
    $('#report-table').DataTable({
      "language": datatableLanguageOptions,
      "autoWidth": false,
      "columnDefs": [{
        "targets": [6],
        "orderable": false,
        "searchable": false
      }]
    });
    $('input[type="search"]').focus();
  });
</script>

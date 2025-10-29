@if (count($transactions) < 1)
  <tr>
    <td colspan="9" class="text-center">
      Tidak ada data
    </td>
  </tr>
@endif
@foreach ($transactions as $transaction)
  <tr>
    <td>{{ $loop->iteration }}</td>
    <td>{{ $transaction->invoice }}</td>
    <td>@currency($transaction->total)</td>
    <td>{{ $transaction->paymentMethod ? $transaction->paymentMethod->name : '-' }}</td>
    <td>{{ $transaction->updated_at }}</td>
    <td>
      <button class="btn btn-xs btn-secondary text-white" data-invoice="{{ $transaction->invoice }}"
        data-grand_total="{{ $transaction->grand_total }}" data-payment_method="{{ $transaction->payment_method }}"
        data-discount="{{ $transaction->discount }}" data-amount="{{ $transaction->amount }}"
        data-change="{{ $transaction->change }}" data-platform="{{ $transaction->platform }}"
        data-note="{{ $transaction->note }}" data-created_at="{{ $transaction->created_at }}"
        data-cashier="{{ $transaction->user->name }}" onclick="showDetail(this)">
        <i class="fas fa-info-circle"></i>
      </button>
      <button id="sale_detail_btn" class="btn btn-xs btn-primary text-white" data-id="{{ $transaction->id }}"
        onclick="getSaleDetail(this)">
        <i class="fas fa-shopping-bag"></i>
      </button>
      <form action="{{ route('sale.destroy', $transaction->id) }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-xs btn-danger text-white"
          onclick="return confirm('Apakah anda yakin ingin menghapus data penjualan ini?')">
          <i class="fas fa-trash"></i>
        </button>
      </form>
    </td>
  </tr>
@endforeach

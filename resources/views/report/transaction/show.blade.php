@foreach ($transaction_details as $transaction_detail)
  <tr>
    <td>{{ $loop->iteration }}</td>
    <td title="{{ $transaction_detail->item->name }}">
      {{ strlen($transaction_detail->item->name) > 100 ? substr($transaction_detail->item->name, 0, 100) . '...' : $transaction_detail->item->name }}
    </td>
    <td>@indo_currency($transaction_detail->item_price)</td>
    <td>{{ $transaction_detail->qty }}</td>
    <td>@indo_currency($transaction_detail->total)</td>
  </tr>
@endforeach

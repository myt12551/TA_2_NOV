@if (count($carts) < 1)
  <tr>
    <td colspan="5" class="text-center">
      <strong>Tidak ada data</strong>
    </td>
  </tr>
@endif
@foreach ($carts as $cart)
  <tr>
    <td title="{{ $cart->item->name }}">
      {{ strlen($cart->item->name) > 25 ? substr($cart->item->name, 0, 25) . '...' : $cart->item->name }}
    </td>
    <td>
      <span>
        @indo_currency(calculate_price($cart->item, $cart->qty))
      </span>
    </td>
    <td width="110">
      <input type="number" class="form-control cart_qty" min="1" max="{{ $cart->item->stock }}"
        value="{{ $cart->qty }}" id="cart_qty_{{ $cart->id }}" data-stock="{{ $cart->item->stock }}"
        onkeyup="updateCart('{{ $cart->id }}', event)" onchange="updateCart('{{ $cart->id }}', event)"></input>
    </td>
    <td>
      <span class="cart_subtotal">
        @indo_currency($cart->subtotal)
      </span>
      <input type="hidden" value="{{ $cart->subtotal }}" class="cart_subtotal_int">
    </td>
    <td>
      <button class="btn btn-danger btn-sm" onclick="deleteCart('{{ $cart->id }}')">
        <i class="fas fa-trash text-white"></i>
      </button>
    </td>
  </tr>
@endforeach

<script>
  function updateCart(id, e) {
    greaterThanZero(id);

    var cart_qty = $('#cart_qty_' + id);
    cart_qty.addClass('border');
    cart_qty.addClass('border-danger');
    if (e.keyCode == 13) {
      $.ajax({
        url: '/cart/' + id,
        type: 'PUT',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
          qty: cart_qty.val()
        },
        dataType: 'json',
        success: function(data) {
          get_cart();
          count_stock();
        }
      });
    }
  }

  function greaterThanZero(id) {
    var cart_qty = $('#cart_qty_' + id);
    if (parseInt(cart_qty.val()) < 1) {
      cart_qty.val(1);
    }

    if (parseInt(cart_qty.val()) > parseInt(cart_qty.attr('data-stock'))) {
      cart_qty.val(cart_qty.attr('data-stock'));
    }
  }

  function deleteCart(id) {
    if (!confirm('Apakah anda yakin ingin menghapus item ini?')) return;
    $.ajax({
      url: '/cart/' + id,
      type: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      dataType: 'json',
      success: function(data) {
        get_cart();
        count_stock();
      }
    });
  }
</script>

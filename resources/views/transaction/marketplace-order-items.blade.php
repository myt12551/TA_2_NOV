{{-- resources/views/transaction/marketplace-order-items.blade.php --}}
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>@indo_currency($item->price)</td>
                    <td>@indo_currency($item->subtotal)</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

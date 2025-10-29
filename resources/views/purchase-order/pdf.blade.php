<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Purchase Order</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 5px; }
        th { background: #f2f2f2; text-align: left; }
    </style>
</head>
<body>
    <h2>Purchase Order</h2>
    <table>
        <tr>
            <th style="width: 25%;">No. PO</th>
            <td>{{ $po->po_number }}</td>
        </tr>
        <tr>
            <th>Supplier</th>
            <td>{{ $po->supplier->name }}</td>
        </tr>
        <tr>
            <th>Tanggal</th>
            <td>{{ \Carbon\Carbon::parse($po->po_date)->format('d M Y') }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>{{ ucfirst($po->status) }}</td>
        </tr>
    </table>

    <h4>Items</h4>
    <table>
        <thead>
            <tr>
                <th style="width: 10%;">#</th>
                <th style="width: 70%;">Nama Barang</th>
                <th style="width: 20%; text-align: right;">Qty</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($po->items as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->product_name }}</td>
                <td style="text-align: right;">{{ $item->quantity }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2" style="text-align: right;">Total Qty</th>
                <th style="text-align: right;">{{ $po->items->sum('quantity') }}</th>
            </tr>
        </tfoot>
    </table>

    @if ($po->invoice_image_path)
    <p><strong>Invoice:</strong> {{ $po->invoice_image_path }}</p>
    @endif
</body>
</html>
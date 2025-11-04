<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Purchase Order #{{ $purchaseOrder->po_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            padding: 0;
            font-size: 24px;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-section table {
            width: 100%;
        }
        .info-section td {
            padding: 2px 0;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th, .items-table td {
            border: 1px solid #000;
            padding: 8px;
        }
        .items-table th {
            background-color: #f0f0f0;
        }
        .text-end {
            text-align: right;
        }
        .footer {
            margin-top: 50px;
        }
        .signature-section {
            width: 100%;
            margin-top: 80px;
        }
        .signature-box {
            float: left;
            width: 30%;
            text-align: center;
        }
        .clear {
            clear: both;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>PURCHASE ORDER</h1>
        <p>Nomor: {{ $purchaseOrder->po_number }}</p>
    </div>

    <div class="info-section">
        <table>
            <tr>
                <td style="width: 50%; vertical-align: top;">
                    <strong>Kepada:</strong><br>
                    {{ $purchaseOrder->supplier->name }}<br>
                    {{ $purchaseOrder->supplier->address }}<br>
                    Telp: {{ $purchaseOrder->supplier->phone }}<br>
                    Email: {{ $purchaseOrder->supplier->email }}
                </td>
                <td style="width: 50%; vertical-align: top;">
                    <table>
                        <tr>
                            <td style="width: 100px">Tanggal PO</td>
                            <td>: {{ $purchaseOrder->po_date->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td>Nomor PR</td>
                            <td>: {{ $purchaseOrder->purchaseRequest->pr_number }}</td>
                        </tr>
                        <tr>
                            <td>Tanggal PR</td>
                            <td>: {{ $purchaseOrder->purchaseRequest->request_date->format('d/m/Y') }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Satuan</th>
                <th>Harga</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($purchaseOrder->items as $item)
                <tr>
                    <td style="width: 30px; text-align: center;">{{ $loop->iteration }}</td>
                    <td>{{ $item->supplierProduct->name }}</td>
                    <td style="width: 80px; text-align: center;">{{ $item->quantity }}</td>
                    <td style="width: 80px; text-align: center;">{{ $item->supplierProduct->unit }}</td>
                    <td style="width: 120px;" class="text-end">{{ number_format($item->price) }}</td>
                    <td style="width: 120px;" class="text-end">{{ number_format($item->quantity * $item->price) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" class="text-end">Total</th>
                <th class="text-end">{{ number_format($purchaseOrder->total_amount) }}</th>
            </tr>
        </tfoot>
    </table>

    @if($purchaseOrder->notes)
        <div style="margin-bottom: 20px;">
            <strong>Catatan:</strong><br>
            {{ $purchaseOrder->notes }}
        </div>
    @endif

    <div class="signature-section">
        <div class="signature-box">
            Dibuat oleh,<br><br><br><br>
            {{ $purchaseOrder->creator->name }}<br>
            {{ now()->format('d/m/Y') }}
        </div>
        <div class="signature-box">
            Disetujui oleh,<br><br><br><br>
            _________________<br>
            Supervisor
        </div>
        <div class="signature-box">
            Diterima oleh,<br><br><br><br>
            _________________<br>
            Supplier
        </div>
        <div class="clear"></div>
    </div>
</body>
</html>
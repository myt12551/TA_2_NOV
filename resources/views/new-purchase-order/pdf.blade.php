<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Purchase Order - {{ $po->po_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .info-item {
            margin-bottom: 10px;
        }
        .info-label {
            color: #666;
            font-size: 12px;
        }
        .info-value {
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
        }
        .total-section {
            text-align: right;
            margin-top: 20px;
        }
        .notes-section {
            margin-top: 30px;
        }
        .signature-section {
            margin-top: 50px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .signature-box {
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 50px;
            width: 200px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>PURCHASE ORDER</h1>
        <h2>{{ $po->po_number }}</h2>
    </div>

    <div class="info-section">
        <div class="info-grid">
            <div>
                <h3>Supplier Information:</h3>
                <div class="info-item">
                    <div class="info-label">Name:</div>
                    <div class="info-value">{{ $po->supplier->name }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Contact Person:</div>
                    <div class="info-value">{{ $po->contact_person }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Phone:</div>
                    <div class="info-value">{{ $po->contact_phone }}</div>
                </div>
            </div>
            <div>
                <h3>PO Information:</h3>
                <div class="info-item">
                    <div class="info-label">PO Date:</div>
                    <div class="info-value">{{ $po->po_date->format('d/m/Y') }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Expected Delivery:</div>
                    <div class="info-value">{{ $po->estimated_delivery_date->format('d/m/Y') }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">PR Reference:</div>
                    <div class="info-value">{{ $po->purchaseRequest->pr_number ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="items-section">
        <h3>Items:</h3>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Product</th>
                    <th>Unit</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($po->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->unit }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->quantity * $item->unit_price, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="total-section">
        <h3>Total Amount: Rp {{ number_format($po->total_amount, 0, ',', '.') }}</h3>
    </div>

    @if($po->notes)
    <div class="notes-section">
        <h3>Notes:</h3>
        <p>{{ $po->notes }}</p>
    </div>
    @endif

    <div class="signature-section">
        <div class="signature-box">
            <p>Prepared by:</p>
            <div class="signature-line"></div>
            <p>{{ auth()->user()->name ?? 'Purchasing Staff' }}</p>
        </div>
        <div class="signature-box">
            <p>Approved by:</p>
            <div class="signature-line"></div>
            <p>Supervisor</p>
        </div>
    </div>
</body>
</html>
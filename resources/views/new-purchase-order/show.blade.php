@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold mb-2">Detail Purchase Order</h1>
            <p class="text-gray-600">{{ $po->po_number }}</p>
        </div>
        <div class="space-x-2">
            <a href="{{ route('new-purchase-orders.pdf', $po->id) }}" 
               class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600"
               target="_blank">
                Download PDF
            </a>
            @if($po->status === 'draft')
            <form action="{{ route('new-purchase-orders.mark-sent', $po->id) }}" 
                  method="POST" class="inline">
                @csrf
                <button type="submit"
                        class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                    Tandai Terkirim
                </button>
            </form>
            @endif
        </div>
    </div>

    <!-- PO Status Timeline -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-semibold mb-4">Status PO</h3>
        <div class="flex justify-between items-center">
            <div class="flex-1">
                <div class="relative">
                    <div class="h-1 bg-gray-200 w-full absolute top-4"></div>
                    <div class="flex justify-between relative">
                        <div class="text-center">
                            <div class="w-8 h-8 bg-green-500 rounded-full mx-auto flex items-center justify-center text-white">
                                <i class="fas fa-check"></i>
                            </div>
                            <p class="mt-2 text-sm">Draft</p>
                            <p class="text-xs text-gray-500">{{ $po->created_at->format('d/m/Y') }}</p>
                        </div>
                        <div class="text-center">
                            <div class="w-8 h-8 {{ $po->status != 'draft' ? 'bg-green-500' : 'bg-gray-300' }} rounded-full mx-auto flex items-center justify-center text-white">
                                <i class="fas fa-paper-plane"></i>
                            </div>
                            <p class="mt-2 text-sm">Terkirim</p>
                            @if($po->sent_at)
                            <p class="text-xs text-gray-500">{{ $po->sent_at->format('d/m/Y') }}</p>
                            @endif
                        </div>
                        <div class="text-center">
                            <div class="w-8 h-8 {{ $po->status == 'confirmed' ? 'bg-green-500' : 'bg-gray-300' }} rounded-full mx-auto flex items-center justify-center text-white">
                                <i class="fas fa-handshake"></i>
                            </div>
                            <p class="mt-2 text-sm">Dikonfirmasi</p>
                            @if($po->confirmation_date)
                            <p class="text-xs text-gray-500">{{ $po->confirmation_date->format('d/m/Y') }}</p>
                            @endif
                        </div>
                        <div class="text-center">
                            <div class="w-8 h-8 {{ $po->status == 'received' ? 'bg-green-500' : 'bg-gray-300' }} rounded-full mx-auto flex items-center justify-center text-white">
                                <i class="fas fa-box"></i>
                            </div>
                            <p class="mt-2 text-sm">Diterima</p>
                            @if($po->goodsReceipt)
                            <p class="text-xs text-gray-500">{{ $po->goodsReceipt->receipt_date->format('d/m/Y') }}</p>
                            @endif
                        </div>
                        <div class="text-center">
                            <div class="w-8 h-8 {{ $po->status == 'invoiced' ? 'bg-green-500' : 'bg-gray-300' }} rounded-full mx-auto flex items-center justify-center text-white">
                                <i class="fas fa-file-invoice"></i>
                            </div>
                            <p class="mt-2 text-sm">Invoice</p>
                            @if($po->invoice)
                            <p class="text-xs text-gray-500">{{ $po->invoice->invoice_date->format('d/m/Y') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6 mb-6">
        <!-- PO Info -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold mb-4">Informasi PO</h3>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-600">Nomor PO</p>
                    <p class="font-medium">{{ $po->po_number }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Tanggal PO</p>
                    <p class="font-medium">{{ $po->po_date->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Status</p>
                    <p class="font-medium">{{ ucfirst($po->status) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Amount</p>
                    <p class="font-medium">Rp {{ number_format($po->total_amount, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Supplier Info -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold mb-4">Informasi Supplier</h3>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-600">Nama Supplier</p>
                    <p class="font-medium">{{ $po->supplier->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Contact Person</p>
                    <p class="font-medium">{{ $po->contact_person }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Telepon</p>
                    <p class="font-medium">{{ $po->contact_phone }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Estimasi Pengiriman</p>
                    <p class="font-medium">{{ $po->estimated_delivery_date->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Items -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-semibold mb-4">Items</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2">Produk</th>
                        <th class="px-4 py-2">Unit</th>
                        <th class="px-4 py-2">Jumlah</th>
                        <th class="px-4 py-2">Harga Satuan</th>
                        <th class="px-4 py-2">Total</th>
                        <th class="px-4 py-2">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($po->items as $item)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $item->product_name }}</td>
                        <td class="px-4 py-2">{{ $item->unit }}</td>
                        <td class="px-4 py-2">{{ $item->quantity }}</td>
                        <td class="px-4 py-2">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                        <td class="px-4 py-2">Rp {{ number_format($item->quantity * $item->unit_price, 0, ',', '.') }}</td>
                        <td class="px-4 py-2">{{ $item->notes }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Action Forms -->
    @if($po->status === 'sent')
    <!-- Confirm Form -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-semibold mb-4">Konfirmasi PO</h3>
        <form action="{{ route('new-purchase-orders.confirm', $po->id) }}" method="POST">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Konfirmasi
                    </label>
                    <input type="date" name="confirmation_date" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Pengiriman yang Dikonfirmasi
                    </label>
                    <input type="date" name="confirmed_delivery_date" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Catatan Supplier
                </label>
                <textarea name="supplier_notes" rows="3"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
            </div>
            <div class="mt-4 flex justify-end">
                <button type="submit"
                        class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                    Konfirmasi PO
                </button>
            </div>
        </form>
    </div>
    @endif

    @if($po->status === 'confirmed')
    <!-- Goods Receipt Form -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-semibold mb-4">Buat Goods Receipt</h3>
        <form action="{{ route('new-purchase-orders.create-gr', $po->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Tanggal Penerimaan
                </label>
                <input type="date" name="receipt_date" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2">Produk</th>
                            <th class="px-4 py-2">Jumlah PO</th>
                            <th class="px-4 py-2">Jumlah Diterima</th>
                            <th class="px-4 py-2">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($po->items as $item)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $item->product_name }}</td>
                            <td class="px-4 py-2">{{ $item->quantity }}</td>
                            <td class="px-4 py-2">
                                <input type="number" name="items[{{ $item->id }}][quantity_received]"
                                       value="{{ $item->quantity }}" required min="0"
                                       class="mt-1 block w-24 rounded-md border-gray-300 shadow-sm">
                            </td>
                            <td class="px-4 py-2">
                                <input type="text" name="items[{{ $item->id }}][notes]"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Catatan Penerimaan
                </label>
                <textarea name="notes" rows="3"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
            </div>
            <div class="mt-4 flex justify-end">
                <button type="submit"
                        class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                    Buat GR
                </button>
            </div>
        </form>
    </div>
    @endif

    @if($po->status === 'received')
    <!-- Invoice Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold mb-4">Buat Invoice</h3>
        <form action="{{ route('new-purchase-orders.create-invoice', $po->id) }}" 
              method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nomor Invoice
                    </label>
                    <input type="text" name="invoice_number" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Invoice
                    </label>
                    <input type="date" name="invoice_date" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Jatuh Tempo
                    </label>
                    <input type="date" name="due_date" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Total Invoice
                    </label>
                    <input type="number" name="amount" required min="0" step="0.01"
                           value="{{ $po->total_amount }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    File Invoice
                </label>
                <input type="file" name="invoice_file" required accept=".pdf,.jpg,.jpeg,.png"
                       class="mt-1 block w-full">
                <p class="mt-1 text-sm text-gray-500">
                    PDF, JPG, JPEG, atau PNG. Maksimal 2MB.
                </p>
            </div>
            <div class="mt-4 flex justify-end">
                <button type="submit"
                        class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                    Buat Invoice
                </button>
            </div>
        </form>
    </div>
    @endif
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold mb-2">Buat Purchase Order</h1>
        <p class="text-gray-600">Dari PR: {{ $pr->pr_number }}</p>
    </div>

    <form action="{{ route('new-purchase-orders.store') }}" method="POST" class="bg-white rounded-lg shadow-md p-6">
        @csrf
        <input type="hidden" name="pr_id" value="{{ $pr->id }}">

        <!-- PR Info -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-3">Informasi Purchase Request</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Nomor PR</p>
                    <p class="font-medium">{{ $pr->pr_number }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Tanggal Request</p>
                    <p class="font-medium">{{ $pr->request_date->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Pemohon</p>
                    <p class="font-medium">{{ $pr->requester->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Status</p>
                    <p class="font-medium">{{ ucfirst($pr->status) }}</p>
                </div>
            </div>
        </div>

        <!-- Supplier Selection -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-3">Informasi Supplier</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Supplier
                    </label>
                    <select name="supplier_id" required 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Pilih Supplier</option>
                        @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Contact Person
                    </label>
                    <input type="text" name="contact_person" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nomor Telepon
                    </label>
                    <input type="text" name="contact_phone" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Estimasi Tanggal Pengiriman
                    </label>
                    <input type="date" name="estimated_delivery_date" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
            </div>
        </div>

        <!-- Items -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-3">Items</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2">Produk</th>
                            <th class="px-4 py-2">Unit</th>
                            <th class="px-4 py-2">Jumlah Request</th>
                            <th class="px-4 py-2">Jumlah PO</th>
                            <th class="px-4 py-2">Harga Satuan</th>
                            <th class="px-4 py-2">Total</th>
                            <th class="px-4 py-2">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pr->items as $item)
                        <tr class="border-b">
                            <td class="px-4 py-2">
                                {{ $item->product_name }}
                                <input type="hidden" name="items[{{ $item->id }}][id]" value="{{ $item->id }}">
                            </td>
                            <td class="px-4 py-2">{{ $item->unit }}</td>
                            <td class="px-4 py-2">{{ $item->quantity }}</td>
                            <td class="px-4 py-2">
                                <input type="number" name="items[{{ $item->id }}][quantity]"
                                       value="{{ $item->quantity }}" required min="1"
                                       class="mt-1 block w-24 rounded-md border-gray-300 shadow-sm"
                                       onchange="updateTotal({{ $item->id }})">
                            </td>
                            <td class="px-4 py-2">
                                <input type="number" name="items[{{ $item->id }}][unit_price]"
                                       required min="0" step="0.01"
                                       class="mt-1 block w-32 rounded-md border-gray-300 shadow-sm"
                                       onchange="updateTotal({{ $item->id }})">
                            </td>
                            <td class="px-4 py-2">
                                <span id="total_{{ $item->id }}">0</span>
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
        </div>

        <!-- Notes -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Catatan PO
            </label>
            <textarea name="notes" rows="3"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
        </div>

        <!-- Submit -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('new-purchase-orders.index') }}"
               class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                Batal
            </a>
            <button type="submit"
                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Buat PO
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function updateTotal(itemId) {
    const qty = document.querySelector(`input[name="items[${itemId}][quantity]"]`).value;
    const price = document.querySelector(`input[name="items[${itemId}][unit_price]"]`).value;
    const total = qty * price;
    document.getElementById(`total_${itemId}`).textContent = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR'
    }).format(total);
}
</script>
@endpush
@endsection
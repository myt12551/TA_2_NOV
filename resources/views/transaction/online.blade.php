{{-- Improved version of resources/views/transaction/online.blade.php
    for marketplace orders. This template displays marketplace orders
    waiting to be picked up (status pending_pickup) and provides
    a simple button to process them immediately.
--}}

<x-layout>
    <x-slot:title>Pesanan Marketplace (Menunggu Diambil)</x-slot:title>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Pesanan Marketplace</h5>
            <a href="{{ route('transaction.index') }}" class="btn btn-outline-secondary btn-sm">← POS / Transaksi</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="marketplace-orders-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Pesanan</th>
                            <th>Nama Pengambil</th>
                            <th>No. HP</th>
                            <th>Total</th>
                            <th>Tanggal</th>
                            <th style="width:160px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $order->code }}</td>
                                <td>{{ $order->pickup_name ?? $order->customer_name }}</td>
                                <td>{{ $order->phone }}</td>
                                <td>@indo_currency($order->total_price)</td>
                                <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d-m-Y H:i') }}</td>
                                <td>
                                    {{-- Tombol detail menampilkan item pesanan --}}
                                    <button class="btn btn-sm btn-info" data-id="{{ $order->id }}"
                                            onclick="showOrderDetail(this)">
                                        <i class="fas fa-list"></i> Detail
                                    </button>
                                    {{-- Tombol proses langsung menyelesaikan pesanan --}}
                                    <button class="btn btn-sm btn-success" data-id="{{ $order->id }}"
                                            onclick="processOrder(this)">
                                        <i class="fas fa-check"></i> Proses
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    Belum ada pesanan marketplace yang menunggu.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pastikan modal #transaction_detail_modal tersedia di layout atau view induk untuk menampilkan detail item --}}

    @push('scripts')
    <script>
        $(function() {
            // Inisialisasi DataTable untuk daftar pesanan marketplace
            const mpTable = $('#marketplace-orders-table').DataTable({
                pageLength: 10,
                order: [[5, 'asc']],
                language: typeof datatableLanguageOptions !== 'undefined' ? datatableLanguageOptions : undefined,
                columnDefs: [
                    { targets: [6], orderable: false, searchable: false }
                ]
            });
            window.tableMarketplaceOrders = mpTable;
        });

        /**
         * Menampilkan detail item pesanan marketplace.
         * Pastikan ada modal #transaction_detail_modal di layout.
         */
        function showOrderDetail(btn) {
            const id = btn.dataset.id;
            $.get(`/transaction/marketplace-online-orders/${id}/items`, function(html) {
                $('#transaction_detail_modal .modal-body').html(html);
                $('#transaction_detail_modal').modal('show');
            }).fail(function() {
                toastr.error('Gagal memuat detail pesanan.');
            });
        }

        /**
         * Proses pesanan marketplace secara otomatis.
         * Meminta konfirmasi, mengirim permintaan POST, dan menghapus baris dari tabel setelah sukses.
         */
        function processOrder(btn) {
            const id = btn.dataset.id;
            if (!confirm('Yakin ingin memproses pesanan ini?')) return;

            $.ajax({
                url: `/transaction/marketplace-online-orders/${id}/process`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(res) {
                    toastr.success(res.message || 'Berhasil diproses.');
                    // hapus baris dari DataTable
                    window.tableMarketplaceOrders
                        .row($(btn).closest('tr'))
                        .remove()
                        .draw();
                },
                error: function(xhr) {
                    const res = xhr.responseJSON;
                    toastr.error(res?.message || 'Terjadi kesalahan.');
                }
            });
        }
    </script>
    @endpush
</x-layout>

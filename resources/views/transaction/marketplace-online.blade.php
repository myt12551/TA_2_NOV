{{-- resources/views/transaction/marketplace-online.blade.php --}}
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
                            <th>No. HP</th>
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
                                    <button class="btn btn-sm btn-info" data-id="{{ $order->id }}"
                                            onclick="showOrderDetail(this)">
                                        <i class="fas fa-list"></i> Detail
                                    </button>
                                    <button class="btn btn-sm btn-success" data-id="{{ $order->id }}"
                                            onclick="processOrder(this)">
                                        <i class="fas fa-check"></i> Proses
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Belum ada pesanan marketplace yang menunggu.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal detail pesanan (untuk showOrderDetail) --}}
    <div class="modal fade" id="transaction_detail_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- konten detail dimuat via AJAX -->
                </div>
            </div>
        </div>
    </div>

    {{-- Modal konfirmasi proses --}}
    <div class="modal fade" id="confirm_process_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Proses Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    {{-- Ringkasan item pesanan akan dimuat di sini --}}
                    <div id="process_detail_body"></div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" id="btn_confirm_process">Selesaikan</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(function() {
            // Inisialisasi DataTable
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
         * Tampilkan detail pesanan ke modal #transaction_detail_modal.
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
         * Ketika tombol Proses diklik:
         * 1. Ambil ringkasan item pesanan via AJAX.
         * 2. Tampilkan di modal konfirmasi.
         * 3. Simpan id pesanan pada button konfirmasi.
         */
        function processOrder(btn) {
            const id = btn.dataset.id;
            $.get(`/transaction/marketplace-online-orders/${id}/items`, function(html) {
                $('#process_detail_body').html(html);
                $('#btn_confirm_process').data('id', id);
                $('#confirm_process_modal').modal('show');
            }).fail(function() {
                toastr.error('Gagal memuat detail pesanan.');
            });
        }

        /**
         * Ketika tombol Selesaikan di modal konfirmasi diklik:
         * - Kirim POST untuk menyelesaikan pesanan.
         * - Jika sukses, tutup modal dan hapus baris pada tabel.
         */
        $('#btn_confirm_process').on('click', function() {
            const id = $(this).data('id');
            $.ajax({
                url: `/transaction/marketplace-online-orders/${id}/process`,
                method: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(res) {
                    toastr.success(res.message || 'Pesanan berhasil diselesaikan.');
                    $('#confirm_process_modal').modal('hide');
                    // Hapus baris dari DataTable
                    window.tableMarketplaceOrders
                        .row($(`#marketplace-orders-table button[data-id="${id}"]`).closest('tr'))
                        .remove()
                        .draw();
                },
                error: function(xhr) {
                    const res = xhr.responseJSON;
                    toastr.error(res?.message || 'Gagal memproses.');
                }
            });
        });
    </script>
    @endpush
</x-layout>

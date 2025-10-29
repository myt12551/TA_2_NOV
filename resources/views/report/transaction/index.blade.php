<x-layout>
  <x-slot:title>Laporan Penjualan</x-slot:title>

  <div class="row">
    <div class="col-md-12">
      @if (session('status'))
        <x-alert type="success" :message="session('status')"></x-alert>
      @endif
      <div class="card">
        <div class="card-header">
          <x-export-button></x-export-button>
        </div>
        <div class="card-body">
          <div class="row mx-2 mt-2">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-5">
                  <div class="row input-daterange">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="start_date">Dari Tanggal</label>
                        <input type="date" class="form-control" id="start_date" name="start"
                          value="{{ Carbon\Carbon::now()->subMonth()->format('Y-m-d') }}">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="end_date">Sampai Tanggal</label>
                        <input type="date" class="form-control" id="end_date" name="end"
                          value="{{ Carbon\Carbon::now()->format('Y-m-d') }}">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="filter_by_status">Status</label>
                    <select class="form-select" id="filter_by_status">
                      <option value="all" selected>Pilih Semua</option>
                      <option value="paid">Lunas</option>
                      <option value="debt">Hutang</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="filter_by_payment_method">Metode Pembayaran</label>
                    <select class="form-select" id="filter_by_payment_method">
                      <option value="all" selected>Pilih Semua</option>
                      @foreach ($payment_methods as $payment_method)
                        <option value="{{ $payment_method->id }}">{{ $payment_method->name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-1">
                  <div class="form-group">
                    <label for="">&nbsp;</label>
                    <button class="form-control btn btn-primary" id="filter_by_date">Cari</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div id="report">@include('report.transaction.table')</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="detail_modal" tabindex="-1" aria-labelledby="detail_modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="detail_modalLabel">Detil Penjualan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <div class="row align-items-center">
                  <label for="invoice" class="col-md-4">Faktur</label>
                  <div class="col-md-8">
                    <input type="text" class="form-control" id="invoice" readonly>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <div class="row align-items-center">
                  <label for="total" class="col-md-3">Total</label>
                  <div class="col-md-9">
                    <input type="text" class="form-control" id="total" readonly>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <div class="row align-items-center">
                  <label for="payment_method" class="col-md-4">Metode Pembayaran</label>
                  <div class="col-md-8">
                    <input type="text" class="form-control" id="payment_method" readonly>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <div class="row align-items-center">
                  <label for="discount" class="col-md-3">Diskon</label>
                  <div class="col-md-9">
                    <input type="text" class="form-control" id="discount" readonly>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <div class="row align-items-center">
                  <label for="amount" class="col-md-4">Uang</label>
                  <div class="col-md-8">
                    <input type="text" class="form-control" id="amount" readonly>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <div class="row align-items-center">
                  <label for="change" class="col-md-3">Kembalian</label>
                  <div class="col-md-9">
                    <input type="text" class="form-control" id="change" readonly>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <div class="row align-items-center">
                  <label for="status" class="col-md-4">Status</label>
                  <div class="col-md-8">
                    <input type="text" class="form-control" id="status" readonly>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <div class="row align-items-center">
                  <label for="time" class="col-md-3">time</label>
                  <div class="col-md-9">
                    <input type="text" class="form-control" id="time" readonly>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <div class="row align-items-center">
                  <label for="cashier" class="col-md-4">Kasir</label>
                  <div class="col-md-8">
                    <input type="text" class="form-control" id="cashier" readonly>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <div class="row align-items-center">
                  <label for="note" class="col-md-3">Catatan</label>
                  <div class="col-md-9">
                    <textarea class="form-control" id="note" readonly></textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="transaction_detail_modal" tabindex="-1"
    aria-labelledby="transaction_detail_modal_label" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="transaction_detail_modal_label">Detil Pembelian</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <table class="table" id="transaction_detail_table">
            <thead class=" text-primary">
              <tr>
                <th><b>No</b></th>
                <th><b>Nama Barang</b></th>
                <th><b>Harga</b></th>
                <th><b>Jumlah</b></th>
                <th><b>Subtotal</b></th>
              </tr>
            </thead>
            <tbody id="transaction_detail_data"></tbody>
          </table>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      $('.input-daterange').datepicker({
        format: "yyyy-mm-dd",
        todayBtn: "linked",
        language: "id"
      });

      $('#filter_by_date').on('click', function() {
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        var payment_method = $('#filter_by_payment_method').val();
        var status = $('#filter_by_status').val();
        if (start_date == '' || end_date == '') {
          return toastr.warning('Tanggal harus diisi');
        }
        $.ajax({
          url: '{{ route('report.transaction.filter') }}',
          type: 'GET',
          data: {
            start_date,
            end_date,
            payment_method,
            status
          },
          success: function(data) {
            $('#report').html(data);
          }
        });
      });
    });

    function getTransactionDetail(th /* this */ ) {
      $('#preloader').show();

      $.ajax({
        url: '/report/transaction/' + th.dataset.id,
        type: 'GET',
        success: function(data) {
          $('#transaction_detail_data').html(data);
          $('#transaction_detail_modal').modal('show');
          $('#preloader').hide();
        }
      });
    }

    function showDetail(th /* this */ ) {
      $('#transaction_detail_table').DataTable().clear().destroy();
      $('#transaction_detail_table').DataTable({
        "language": datatableLanguageOptions,
        "autoWidth": false
      });

      $('#invoice').val(th.dataset.invoice);
      $('#total').val(indo_currency(th.dataset.total, true));
      $('#payment_method').val(th.dataset.payment_method);
      $('#discount').val(indo_currency(th.dataset.discount, true));
      $('#amount').val(th.dataset.amount ? indo_currency(th.dataset.amount, true) : '-');
      $('#change').val(th.dataset.change ? indo_currency(th.dataset.change, true) : '-');
      $('#status').val(th.dataset.status ? th.dataset.status : '-');
      $('#cashier').val(th.dataset.cashier);
      $('#time').val(th.dataset.updated_at + ' | ' + th.dataset.time);
      $('#note').val(th.dataset.note);
      $('#detail_modal').modal('show');
    }

    function exportData(type) {
      // Get current filter values
      var start_date = $('#start_date').val();
      var end_date = $('#end_date').val();
      var payment_method = $('#filter_by_payment_method').val();
      var status = $('#filter_by_status').val();

      window.location.href = '{{ route("report.export-sale") }}?type=' + type
        + '&start_date=' + start_date
        + '&end_date=' + end_date
        + '&payment_method=' + payment_method
        + '&status=' + status;
    }
  </script>
</x-layout>

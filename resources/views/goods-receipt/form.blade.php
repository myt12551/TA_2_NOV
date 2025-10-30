@extends('layouts.app')

{{-- Gunakan Bootstrap untuk tampilan ini --}}
@section('use_bootstrap', true)

@section('title', 'Buat Goods Receipt')

@section('content')
    {{-- Baris judul dan tombol kembali --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5>Buat Goods Receipt</h5>
        <a href="{{ route('new-purchase-orders.show', $po->id) }}" class="btn btn-sm btn-secondary">Kembali</a>
    </div>

    <div class="card">
        <div class="card-body">
            {{-- Form pembuatan GR --}}
            <form action="{{ route('goods-receipts.store', $po->id) }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="receipt_date" class="form-label">Tanggal Penerimaan</label>
                        <input type="date" name="receipt_date" id="receipt_date" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="notes" class="form-label">Catatan</label>
                        <textarea name="notes" id="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <h6 class="mt-3">Daftar Item</h6>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered mb-3">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Barang</th>
                                <th>Jumlah Dipesan</th>
                                <th>Jumlah Diterima</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($po->items as $index => $item)
                                <tr>
                                    <td>{{ $item->product_name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>
                                        <input type="hidden" name="items[{{ $index }}][product_name]" value="{{ $item->product_name }}">
                                        <input type="number" name="items[{{ $index }}][quantity_received]" class="form-control" required>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <button type="submit" class="btn btn-primary">Simpan GR</button>
            </form>
        </div>
    </div>
@endsection

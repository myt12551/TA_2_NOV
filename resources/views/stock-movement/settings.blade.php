@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Pengaturan Analisis Stok</h1>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-cog me-1"></i>
            Konfigurasi Parameter Analisis
        </div>
        <div class="card-body">
            <form action="{{ route('stock-movement.settings.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Periode Analisis (Hari)</label>
                            <input type="number" class="form-control" name="analysis_period" 
                                   value="{{ old('analysis_period', $settings->analysis_period) }}" 
                                   required min="1">
                            <div class="form-text">Jumlah hari untuk menghitung rata-rata penjualan</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Lead Time Supplier (Hari)</label>
                            <input type="number" class="form-control" name="lead_time_days" 
                                   value="{{ old('lead_time_days', $settings->lead_time_days) }}" 
                                   required min="1">
                            <div class="form-text">Estimasi waktu pengiriman dari supplier</div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Ambang Batas Fast Moving (unit/hari)</label>
                            <input type="number" class="form-control" name="fast_moving_threshold" 
                                   value="{{ old('fast_moving_threshold', $settings->fast_moving_threshold) }}" 
                                   required step="0.1" min="0">
                            <div class="form-text">Barang dengan rata-rata penjualan di atas nilai ini dianggap Fast Moving</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Ambang Batas Slow Moving (unit/hari)</label>
                            <input type="number" class="form-control" name="slow_moving_threshold" 
                                   value="{{ old('slow_moving_threshold', $settings->slow_moving_threshold) }}" 
                                   required step="0.1" min="0">
                            <div class="form-text">Barang dengan rata-rata penjualan di bawah nilai ini dianggap Slow Moving</div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Safety Stock (Hari)</label>
                            <input type="number" class="form-control" name="safety_stock_days" 
                                   value="{{ old('safety_stock_days', $settings->safety_stock_days) }}" 
                                   required min="0">
                            <div class="form-text">Stok pengaman dalam hari penjualan</div>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
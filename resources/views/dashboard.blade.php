<x-layout>
  <x-slot:title>Dashboard</x-slot:title>

  @if (session('status'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <b>{{ session('status') }}</b>
    </div>
  @endif

  <div class="row">
    <!-- Kartu: Total Barang -->
    <div class="col-md-4">
      <a href="{{ route('item.index') }}">
        <div class="card card-hover">
          <div class="box bg-danger">
            <div class="row align-items-center">
              <div class="col-md-8">
                <h2 class="fw-bold text-white">{{ $total_items }}</h2>
                <h6 class="text-white fw-normal">Total Barang</h6>
              </div>
              <div class="col-md-4">
                <h1 class="text-white float-end">
                  <i class="mdi mdi-package-variant-closed" style="font-size: 80px;"></i>
                </h1>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>

    <!-- Kartu: Total Penjualan -->
    <div class="col-md-3">
      <a href="#">
        <div class="card card-hover">
          <div class="box bg-info">
            <div class="row align-items-center">
              <div class="col-md-8">
                <h2 class="fw-bold text-white">{{ $total_transactions }}</h2>
                <h6 class="text-white fw-normal">Total Penjualan</h6>
              </div>
              <div class="col-md-4">
                <h1 class="text-white float-end">
                  <i class="mdi mdi-cart" style="font-size: 80px;"></i>
                </h1>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>

    <!-- Kartu: Total Pendapatan -->
    <div class="col-md-5">
      <a href="#">
        <div class="card card-hover">
          <div class="box bg-success">
            <div class="row align-items-center">
              <div class="col-md-8">
                <h2 class="fw-bold text-white">@indo_currency($total_income)</h2>
                <h6 class="text-white fw-normal">Total Pendapatan</h6>
              </div>
              <div class="col-md-4">
                <h1 class="text-white float-end">
                  <i class="mdi mdi-cash-multiple" style="font-size: 80px;"></i>
                </h1>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>
  </div>

  <div class="row">
    <!-- Kartu: Pendapatan Hari Ini -->
    <div class="col-md-6">
      <a href="#">
        <div class="card card-hover">
          <div class="box bg-primary">
            <div class="row align-items-center">
              <div class="col-md-8">
                <h2 class="fw-bold text-white">@indo_currency($income_today)</h2>
                <h6 class="text-white fw-normal">Pendapatan Hari Ini</h6>
              </div>
              <div class="col-md-4">
                <h1 class="text-white float-end">
                  <i class="mdi mdi-cash-multiple" style="font-size: 80px;"></i>
                </h1>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>

    <!-- Kartu: Pendapatan Bulan Ini -->
    <div class="col-md-6">
      <a href="#">
        <div class="card card-hover">
          <div class="box bg-cyan">
            <div class="row align-items-center">
              <div class="col-md-8">
                <h2 class="fw-bold text-white">Rp. 0</h2>
                <h6 class="text-white fw-normal">Pendapatan Bulan Ini</h6>
              </div>
              <div class="col-md-4">
                <h1 class="text-white float-end">
                  <i class="mdi mdi-cash-multiple" style="font-size: 80px;"></i>
                </h1>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>
  </div>

  <!-- Kartu: Purchase Order -->
  @if (auth()->user()->role === 'supervisor' || auth()->user()->role === 'admin')
  <div class="row">
    <div class="col-md-12">
      <a href="{{ route('purchase-orders.index') }}">
        <div class="card card-hover">
          <div class="box bg-warning">
            <div class="row align-items-center">
              <div class="col-md-10">
                <h2 class="fw-bold text-white">Manajemen Purchase Order</h2>
                <h6 class="text-white fw-normal">Buat dan pantau PO ke supplier</h6>
              </div>
              <div class="col-md-2">
                <h1 class="text-white float-end">
                  <i class="mdi mdi-file-document-edit" style="font-size: 80px;"></i>
                </h1>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>
  </div>
  @endif
</x-layout>

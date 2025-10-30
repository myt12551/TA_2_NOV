<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Keranjang - Teaching Factory Marketplace</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        :root {
            --bs-primary-rgb: 13, 110, 253;
            --bs-success-rgb: 25, 135, 84;
        }

        body {
            background-color: #f8f9fa;
        }
        
        .navbar-marketplace {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,.05);
        }

        .product-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 0.5rem;
        }

        .table > :not(caption) > * > * {
            padding: 1rem;
        }

        .qty-input {
            width: 70px;
            text-align: center;
        }

        .btn-update {
            transition: all 0.2s;
        }

        .btn-update:hover {
            transform: translateY(-2px);
        }

        .empty-cart {
            text-align: center;
            padding: 3rem;
        }

        .empty-cart i {
            font-size: 4rem;
            color: #dee2e6;
            margin-bottom: 1.5rem;
        }

        .cart-summary {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-marketplace sticky-top mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('marketplace.index') }}">Teaching Factory Marketplace</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a href="{{ route('marketplace.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-left me-2"></i>
                            Lanjut Belanja
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-4">
        <div class="row">
            <div class="col-lg-8">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0">Keranjang Belanja</h4>
                    @if(!empty($rows) && count($rows) > 0)
                        <form action="{{ route('marketplace.cart.clear') }}" method="POST"
                              onsubmit="return confirm('Kosongkan keranjang?')">
                            @csrf
                            <button class="btn btn-outline-danger">
                                <i class="bi bi-trash me-2"></i>
                                Kosongkan
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Alerts -->
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle me-2"></i>
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        {{ session('error') }}
                    </div>
                @endif
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="list-unstyled mb-0">
                            @foreach ($errors->all() as $error)
                                <li>
                                    <i class="bi bi-exclamation-circle me-2"></i>
                                    {{ $error }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @php
                    $hasRows = !empty($rows) && count($rows) > 0;
                @endphp

                <!-- Cart Content -->
                @if(!$hasRows)
                    <div class="card border-0 shadow-sm">
                        <div class="card-body empty-cart">
                            <i class="bi bi-cart"></i>
                            <h5>Keranjang Anda masih kosong</h5>
                            <p class="text-muted">
                                Jelajahi marketplace kami dan tambahkan produk ke keranjang
                            </p>
                            <a href="{{ route('marketplace.index') }}" class="btn btn-primary">
                                <i class="bi bi-shop me-2"></i>
                                Belanja Sekarang
                            </a>
                        </div>
                    </div>
                @else
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4">Produk</th>
                                            <th style="width:140px;">Jumlah</th>
                                            <th style="width:160px;">Harga</th>
                                            <th style="width:180px;">Subtotal</th>
                                            <th style="width:90px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="border-top-0">
                                        @foreach($rows as $row)
                                            @php
                                                $item = $row['item'];
                                                $qty = (int) $row['qty'];
                                                $price = (float) $row['price'];
                                                $subtotal = (float) $row['subtotal'];
                                            @endphp
                                            <tr>
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $item->photo_url ?? asset('images/no-image.png') }}"
                                                             class="product-img me-3">
                                                        <div>
                                                            <h6 class="mb-1">{{ $item->name }}</h6>
                                                            <div class="text-muted small">Kode: {{ $item->code }}</div>
                                                            <div class="mt-1">
                                                                <span class="badge bg-{{ (int)$item->stock > 0 ? 'success-subtle text-success' : 'secondary-subtle text-secondary' }}">
                                                                    <i class="bi bi-box me-1"></i>
                                                                    Stok: {{ (int)$item->stock }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <form action="{{ route('marketplace.cart.update') }}" 
                                                          method="POST" 
                                                          class="d-flex align-items-center gap-2">
                                                        @csrf
                                                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                                                        <input type="number" 
                                                               name="qty" 
                                                               min="1"
                                                               max="{{ max(1, (int)$item->stock) }}"
                                                               value="{{ $qty }}" 
                                                               class="form-control form-control-sm qty-input">
                                                        <button class="btn btn-sm btn-outline-primary btn-update">
                                                            <i class="bi bi-arrow-clockwise"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                                <td>Rp {{ number_format($price, 0, ',', '.') }}</td>
                                                <td class="fw-bold">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                                <td>
                                                    <form action="{{ route('marketplace.cart.remove') }}" 
                                                          method="POST"
                                                          onsubmit="return confirm('Hapus item ini dari keranjang?')">
                                                        @csrf
                                                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                                                        <button class="btn btn-sm btn-outline-danger">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            @if($hasRows)
                <div class="col-lg-4">
                    <div class="cart-summary card border-0 shadow-sm sticky-top" style="top: 5rem;">
                        <h5 class="mb-4">Ringkasan Belanja</h5>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Total Item</span>
                            <span>{{ count($rows) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="text-muted">Total Harga</span>
                            <span class="h5 mb-0 fw-bold">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-grid">
                            <a href="{{ route('marketplace.checkout') }}" class="btn btn-primary btn-lg">
                                <i class="bi bi-credit-card me-2"></i>
                                Lanjut ke Pembayaran
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-top mt-5">
        <div class="container py-4">
            <div class="text-center text-muted small">
                &copy; {{ date('Y') }} Teaching Factory Marketplace. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

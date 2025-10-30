<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <script>
        window.Laravel = {!! json_encode(['csrfToken' => csrf_token()]) !!};
    </script>
    
    <title>Teaching Factory Marketplace</title>
    
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

        .hero-section {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            border-radius: 1rem;
            overflow: hidden;
        }

        .hero-image {
            max-width: 100%;
            height: auto;
            filter: drop-shadow(0 0 1rem rgba(0,0,0,.2));
        }

        .card-product {
            height: 100%;
            transition: transform 0.2s, box-shadow 0.2s;
            border: none;
            border-radius: 1rem;
            overflow: hidden;
        }
        
        .card-product:hover {
            transform: translateY(-5px);
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15);
        }

        .product-img-container {
            aspect-ratio: 1;
            overflow: hidden;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-img {
            object-fit: contain;
            width: 80%;
            height: 80%;
            transition: transform 0.3s;
        }

        .card-product:hover .product-img {
            transform: scale(1.05);
        }

        .badge-overlay {
            position: absolute;
            top: 1rem;
            right: 1rem;
            z-index: 2;
        }

        .category-badge {
            position: absolute;
            bottom: 1rem;
            left: 1rem;
            z-index: 2;
        }

        .btn-cart {
            background: rgba(var(--bs-primary-rgb), 1);
            color: white;
            border: none;
            transition: all 0.3s;
        }

        .btn-cart:hover {
            background: rgba(var(--bs-primary-rgb), 0.9);
            transform: translateY(-2px);
        }

        .price {
            color: rgba(var(--bs-success-rgb), 1);
            font-weight: 600;
            font-size: 1.25rem;
        }

        .product-title {
            font-weight: 500;
            line-height: 1.4;
            margin-bottom: 0.5rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .filter-button {
            transition: all 0.2s;
        }

        .filter-button:hover {
            transform: translateY(-2px);
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-state i {
            font-size: 4rem;
            color: #dee2e6;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-marketplace sticky-top mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Teaching Factory Marketplace</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item me-3">
                        <a href="{{ route('marketplace.cart') }}" class="btn btn-outline-primary position-relative">
                            <i class="bi bi-cart me-2"></i>
                            Keranjang
                            @if(($cartCount ?? 0) > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <form method="POST" action="{{ route('customer.logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>
                                    LOGOUT
                                </button>
                            </form>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <!-- Hero Section -->
        <div class="hero-section mb-4 text-white">
            <div class="container py-5">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h1 class="display-5 fw-bold mb-4">Belanja Produk Unggulan</h1>
                        <p class="lead mb-4">
                            Pilih produk berkualitas dengan harga terbaik, pesan dan ambil langsung di lokasi kami.
                        </p>
                        <div class="d-flex flex-wrap gap-3">
                            <a href="#products" class="btn btn-outline-light btn-lg">
                                <i class="bi bi-grid me-2"></i>
                                Jelajahi Produk
                            </a>
                            <a href="{{ route('marketplace.cart') }}" class="btn btn-light btn-lg">
                                <i class="bi bi-cart me-2"></i>
                                Lihat Keranjang
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6 d-none d-lg-block text-end">
                        <img src="{{ asset('images/hero-market.png') }}"
                            alt="Marketplace Illustration"
                            class="hero-image"
                            onerror="this.style.display='none'">
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                    <button class="btn btn-primary filter-button">
                        <i class="bi bi-plus-lg me-2"></i>
                        Terbaru
                    </button>
                    <button class="btn btn-outline-primary filter-button">
                        <i class="bi bi-graph-up me-2"></i>
                        Terlaris
                    </button>
                    <button class="btn btn-outline-primary filter-button">
                        <i class="bi bi-tag me-2"></i>
                        Promo
                    </button>
                    <button class="btn btn-outline-primary filter-button">
                        <i class="bi bi-grid me-2"></i>
                        Semua Produk
                    </button>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4" id="products">
            @if($items->count() === 0)
                <div class="col-12">
                    <div class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <h3 class="h4 mb-3">Belum Ada Produk</h3>
                        <p class="text-muted">
                            Mohon maaf, saat ini belum ada produk yang tersedia.
                            Silakan cek kembali dalam beberapa waktu.
                        </p>
                    </div>
                </div>
            @else
                @foreach($items as $item)
                    <div class="col">
                        @include('marketplace.partials.product-card', ['item' => $item])
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Pagination -->
        @if($items->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $items->links() }}
            </div>
        @endif
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $item->name }} - Teaching Factory Marketplace</title>
    
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

        .product-image {
            max-height: 400px;
            object-fit: contain;
            transition: transform 0.3s ease;
        }

        .product-image:hover {
            transform: scale(1.05);
            cursor: zoom-in;
        }

        .modal {
            background-color: rgba(0, 0, 0, 0.9);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-transparent border-0">
                <div class="d-flex justify-content-end p-3">
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center p-0">
                    <img id="modalImage" class="modal-image" alt="Product Image">
                </div>
            </div>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-marketplace sticky-top mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('marketplace.index') }}">Teaching Factory Marketplace</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item me-3">
                        <a href="{{ route('marketplace.cart') }}" class="btn btn-outline-primary position-relative">
                            <i class="bi bi-cart me-2"></i>
                            Keranjang
                            @if(session()->has('marketplace_cart') && count(session('marketplace_cart')) > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ count(session('marketplace_cart')) }}
                                </span>
                            @endif
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('marketplace.index') }}" class="text-decoration-none">
                        <i class="bi bi-house me-1"></i>
                        Marketplace
                    </a>
                </li>
                <li class="breadcrumb-item active">{{ $item->name }}</li>
            </ol>
        </nav>

        <!-- Product Detail -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="row g-0">
                <!-- Image Section -->
                <div class="col-lg-6 p-4 bg-light">
                    <div class="text-center">
                        <img src="{{ $item->photo_url }}"
                             alt="{{ $item->name }}"
                             class="product-image"
                             onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}'"
                             data-bs-toggle="modal"
                             data-bs-target="#imageModal">
                        @if($item->picture)
                            <div class="mt-2 text-muted small">
                                <i class="bi bi-zoom-in me-1"></i>
                                Klik untuk memperbesar
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Product Info -->
                <div class="col-lg-6">
                    <div class="p-4 p-lg-5">
                        <h1 class="h2 fw-bold mb-2">{{ $item->name }}</h1>
                        <p class="text-muted mb-4">
                            <i class="bi bi-upc me-2"></i>
                            Kode: {{ $item->code }}
                        </p>
                        
                        <div class="border-top my-4"></div>
                        
                        <div class="h2 fw-bold text-primary mb-4">
                            {{ $item->selling_price_formatted }}
                        </div>

                        @if(!empty($item->description))
                            <p class="text-muted mb-4">{{ $item->description }}</p>
                        @endif

                        <div class="mb-4">
                            <span class="stock-badge {{ (int)$item->stock > 0 ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                <i class="bi bi-box me-2"></i>
                                Stok: {{ (int)$item->stock }}
                            </span>
                        </div>

                        <form action="{{ route('marketplace.cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="item_id" value="{{ $item->id }}">
                            
                            <div class="row g-3">
                                <div class="col-sm-4">
                                    <label for="qty" class="form-label">Jumlah</label>
                                    <input type="number"
                                           id="qty"
                                           name="qty"
                                           class="form-control"
                                           value="1"
                                           min="1"
                                           max="{{ max(1, (int)$item->stock) }}"
                                           @if((int)$item->stock <= 0) disabled @endif>
                                </div>
                                
                                <div class="col-12">
                                    <div class="d-grid gap-2 d-sm-flex">
                                        <button type="submit"
                                                class="btn btn-cart flex-fill py-3"
                                                @if((int)$item->stock <= 0) disabled @endif>
                                            <i class="bi bi-cart-plus me-2"></i>
                                            Tambah ke Keranjang
                                        </button>
                                        
                                        <button type="submit"
                                                formaction="{{ route('marketplace.cart.add') }}?checkout=true"
                                                class="btn btn-order flex-fill py-3"
                                                @if((int)$item->stock <= 0) disabled @endif>
                                            <i class="bi bi-check2-circle me-2"></i>
                                            Pesan Sekarang
                                        </button>
                                    </div>
                                </div>

                                <div class="col-12 text-center mt-4">
                                    <a href="{{ route('marketplace.index') }}"
                                       class="text-decoration-none">
                                        <i class="bi bi-arrow-left me-2"></i>
                                        Kembali ke Marketplace
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
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

    @if(config('app.debug'))
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1000">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Debug Info</h6>
                    <div class="small">
                        <div class="mb-1">Picture Field: {{ $item->picture ?? 'null' }}</div>
                        <div class="mb-1">Image Path: {{ asset('storage/items/' . ($item->picture ?? 'default.png')) }}</div>
                        <div class="mb-1">Storage Path: {{ storage_path('app/public/items/' . ($item->picture ?? '')) }}</div>
                        <div class="mb-1">File Exists: {{ Storage::disk('public')->exists('items/' . ($item->picture ?? '')) ? 'Yes' : 'No' }}</div>
                        <div class="mb-1">Category: {{ $item->category->name ?? 'Tidak ada kategori' }}</div>
                        <div>Stock: {{ $item->stock }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const productImage = document.querySelector('.product-image');
            const modalImg = document.getElementById('modalImage');

            if (productImage && !productImage.src.includes('no-image.png')) {
                productImage.addEventListener('click', function() {
                    modalImg.src = this.src;
                });
            }
        });
    </script>
</body>
</html>

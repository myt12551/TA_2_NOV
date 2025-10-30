<div class="card card-product shadow-sm h-100">
    {{-- Image Container --}}
    <div class="product-img-container p-4">
        <img src="{{ $item->photo_url }}"
             alt="{{ $item->name }}"
             class="product-img"
             onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}'">
        
        {{-- Stock Badge --}}
        <div class="badge-overlay">
            @if((int)$item->stock <= 5 && (int)$item->stock > 0)
                <span class="badge text-bg-warning">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    Stok Terbatas
                </span>
            @elseif((int)$item->stock <= 0)
                <span class="badge text-bg-danger">
                    <i class="bi bi-x-circle me-1"></i>
                    Habis
                </span>
            @endif
        </div>

        {{-- Category Badge --}}
        @if($item->category)
            <span class="badge text-bg-primary category-badge">
                <i class="bi bi-tag me-1"></i>
                {{ $item->category->name }}
            </span>
        @endif
    </div>

    <div class="card-body pt-0 px-4 pb-4">
        <h5 class="product-title mb-3">{{ $item->name }}</h5>
        
        <div class="d-flex justify-content-between align-items-center mb-3">
            <small class="text-muted">
                <i class="bi bi-upc me-1"></i>
                {{ $item->code }}
            </small>
            <small class="text-muted">
                <i class="bi bi-box me-1"></i>
                Stok: {{ (int)$item->stock }}
            </small>
        </div>

        <div class="price mb-4">
            {{ $item->selling_price_formatted }}
        </div>

        <form action="{{ route('marketplace.cart.add') }}" method="POST">
            @csrf
            <input type="hidden" name="item_id" value="{{ $item->id }}">
            <input type="hidden" name="qty" value="1">
            <div class="d-grid gap-2">
                <a href="{{ route('marketplace.show', $item->id) }}" 
                   class="btn btn-outline-primary">
                    <i class="bi bi-eye me-1"></i>
                    Detail
                </a>
                <button type="submit" 
                        class="btn btn-cart" 
                        @if((int)$item->stock <= 0) disabled @endif>
                    <i class="bi bi-cart-plus me-1"></i>
                    Keranjang
                </button>
            </div>
        </form>
    </div>
</div>

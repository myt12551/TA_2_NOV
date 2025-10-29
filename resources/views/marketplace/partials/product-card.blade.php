<div class="card card-product h-100 shadow-sm border-0">
  <img src="{{ $item->photo_url ?? asset('images/no-image.png') }}"
       class="card-img-top"
       alt="{{ $item->name }}"
       style="object-fit:cover;height:180px;">

  <div class="card-body d-flex flex-column">
    <div class="d-flex justify-content-between align-items-start mb-1">
      <h6 class="card-title mb-0 text-truncate me-2" title="{{ $item->name }}">{{ $item->name }}</h6>
      @if((int)$item->stock <= 5 && (int)$item->stock > 0)
        <span class="badge text-bg-warning">Low</span>
      @elseif((int)$item->stock <= 0)
        <span class="badge text-bg-secondary">Habis</span>
      @endif
    </div>

    <small class="text-muted d-block mb-2">Kode: {{ $item->code }}</small>

    <div class="mt-auto">
      <div class="fw-bold fs-6 mb-3">
        {{ $item->selling_price_formatted ?? ('Rp ' . number_format((float)$item->selling_price, 0, ',', '.')) }}
      </div>

      <div class="d-grid gap-2">
        <a href="{{ route('marketplace.show', $item->id) }}" class="btn btn-outline-primary btn-sm">
          Detail
        </a>

        <form action="{{ route('marketplace.cart.add') }}" method="POST">
          @csrf
          <input type="hidden" name="item_id" value="{{ $item->id }}">
          <input type="hidden" name="qty" value="1">
          <button class="btn btn-primary btn-sm" @if((int)$item->stock <= 0) disabled @endif>
            + Keranjang
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

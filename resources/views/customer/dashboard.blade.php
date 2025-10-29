@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6">
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-semibold">Marketplace Teaching Factory</h1>
    <form method="GET" action="{{ route('customer.dashboard') }}" class="flex gap-2">
  <input type="text" name="q" value="{{ $q ?? request('q') ?? '' }}" placeholder="Cari produk..." class="border rounded-lg px-3 py-2">
  <button class="px-4 py-2 rounded-lg bg-black text-white">Cari</button>
</form>

  </div>

  @if (session('success'))
    <div class="mb-4 p-3 rounded-lg border border-green-300 bg-green-50 text-green-700">{{ session('success') }}</div>
  @endif

  @if ($items->count() === 0)
    <p class="text-gray-500">Belum ada produk.</p>
  @else
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
    @foreach ($items as $item)
      <div class="bg-white rounded-xl shadow p-4 flex flex-col">
        @if (!empty($item->picture))
          <img src="{{ asset('storage/'.$item->picture) }}" alt="{{ $item->name }}" class="w-full h-40 object-cover mb-3 rounded-lg">
        @endif

        <div class="flex-1">
          <h3 class="font-semibold text-lg">{{ $item->name }}</h3>
          <p class="text-sm text-gray-500">Kode: {{ $item->code }}</p>
          @if(isset($item->stock))
            <p class="text-sm mt-1">Stok: {{ $item->stock }}</p>
          @endif
          <p class="mt-2 font-semibold">Rp {{ number_format((float)($item->price ?? 0),0,',','.') }}</p>
        </div>

        <form method="POST" action="{{ route('marketplace.cart.add') }}" class="mt-3 flex items-center gap-2">
          @csrf
          <input type="hidden" name="item_id" value="{{ $item->id }}">
          <input type="number" name="qty" min="1" value="1" class="w-20 border rounded-lg px-2 py-2">
          <button class="flex-1 rounded-lg px-4 py-2 bg-black text-white">Tambah</button>
        </form>
      </div>
    @endforeach
  </div>

  <div class="mt-6">
    {{ $items->links() }}
  </div>
  @endif

  <div class="mt-6 text-right">
    <a href="{{ route('marketplace.cart') }}" class="underline">Lihat Keranjang</a>
  </div>
</div>
@endsection

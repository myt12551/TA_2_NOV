@extends('layouts.app')

@section('title', 'Dashboard Customer')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl shadow-lg mb-8">
        <div class="px-8 py-12 text-white">
            <h1 class="text-3xl font-bold mb-4">Selamat Datang di Dashboard Customer</h1>
            <p class="text-indigo-100 mb-6">Temukan berbagai produk berkualitas dengan harga terbaik.</p>
            <div class="flex space-x-4">
                <a href="{{ route('marketplace.index') }}" 
                   class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-indigo-600 bg-white hover:bg-indigo-50 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    Belanja Sekarang
                </a>
                <a href="{{ route('marketplace.order.index') }}"
                   class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-indigo-700 hover:bg-indigo-800 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Pesanan Saya
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-indigo-100 rounded-lg p-3">
                    <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Total Pesanan</h3>
                    <p class="text-2xl font-bold text-indigo-600">{{ auth()->user()->marketplaceOrders()->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Pesanan Selesai</h3>
                    <p class="text-2xl font-bold text-green-600">{{ auth()->user()->marketplaceOrders()->where('status', 'completed')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-yellow-100 rounded-lg p-3">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Menunggu Proses</h3>
                    <p class="text-2xl font-bold text-yellow-600">{{ auth()->user()->marketplaceOrders()->where('status', 'pending')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-medium text-gray-900">Pesanan Terakhir</h2>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse(auth()->user()->marketplaceOrders()->latest()->take(5)->get() as $order)
                <div class="p-6 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900">Order #{{ $order->id }}</p>
                            <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="mt-4 grid grid-cols-2 gap-4">
                        @foreach($order->items()->take(2)->get() as $item)
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden bg-gray-100">
                                    <img src="{{ $item->item->photo_url }}" 
                                         alt="{{ $item->item->name }}"
                                         class="w-full h-full object-cover"
                                         onerror="this.src='{{ asset('images/no-image.png') }}'">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ $item->item->name }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ $item->quantity }}x @ Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('marketplace.order.show', $order->id) }}" 
                           class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-700">
                            Lihat Detail
                            <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-gray-500">
                    Belum ada pesanan
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

<x-layout>
    <x-slot:title>Daftar Produk Supplier</x-slot:title>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Daftar Produk Supplier</h5>
                        <a href="{{ route('supplier-products.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Produk
                        </a>
                    </div>
                    <div class="card-body">
                        <form method="GET" class="mb-4">
                            <div class="row">
                                <div class="col-md-4">
                                    <select name="supplier_id" class="form-select" onchange="this.form.submit()">
                                        <option value="">Semua Supplier</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" 
                                                {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Supplier</th>
                                        <th>Nama Produk</th>
                                        <th>Item Referensi</th>
                                        <th>Harga</th>
                                        <th>Min. Order</th>
                                        <th>Lead Time</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($supplierProducts as $product)
                                        <tr>
                                            <td>{{ $product->supplier->name }}</td>
                                            <td>{{ $product->product_name }}</td>
                                            <td>{{ $product->item->name }}</td>
                                            <td class="text-end">{{ number_format($product->price) }}</td>
                                            <td class="text-center">
                                                {{ $product->min_order ?? '-' }}
                                                {{ $product->item->unit }}
                                            </td>
                                            <td class="text-center">
                                                {{ $product->lead_time ? $product->lead_time . ' hari' : '-' }}
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('supplier-products.edit', $product->id) }}" 
                                                       class="btn btn-info btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('supplier-products.destroy', $product->id) }}" 
                                                          method="POST" 
                                                          onsubmit="return confirm('Yakin ingin menghapus produk ini?')"
                                                          class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada data produk</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $supplierProducts->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
<x-layout>
    <x-slot:title>{{ isset($supplierProduct) ? 'Edit' : 'Tambah' }} Produk Supplier</x-slot:title>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ isset($supplierProduct) ? 'Edit' : 'Tambah' }} Produk Supplier</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ isset($supplierProduct) 
                                      ? route('supplier-products.update', $supplierProduct->id) 
                                      : route('supplier-products.store') }}" 
                              method="POST">
                            @csrf
                            @if(isset($supplierProduct))
                                @method('PUT')
                            @endif

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Supplier</label>
                                        <select name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror" required>
                                            <option value="">Pilih Supplier</option>
                                            @foreach($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}"
                                                        {{ old('supplier_id', $supplierProduct->supplier_id ?? '') == $supplier->id ? 'selected' : '' }}>
                                                    {{ $supplier->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('supplier_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label required">Item Referensi</label>
                                        <select name="item_id" class="form-select @error('item_id') is-invalid @enderror" required>
                                            <option value="">Pilih Item</option>
                                            @foreach($items as $item)
                                                <option value="{{ $item->id }}"
                                                        {{ old('item_id', $supplierProduct->item_id ?? '') == $item->id ? 'selected' : '' }}
                                                        data-unit="{{ $item->unit }}">
                                                    {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('item_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label required">Nama Produk</label>
                                        <input type="text" name="product_name" 
                                               class="form-control @error('product_name') is-invalid @enderror"
                                               value="{{ old('product_name', $supplierProduct->product_name ?? '') }}" required>
                                        @error('product_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Harga</label>
                                        <input type="number" name="price" 
                                               class="form-control @error('price') is-invalid @enderror"
                                               value="{{ old('price', $supplierProduct->price ?? '') }}" 
                                               min="0" step="1" required>
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Minimum Order</label>
                                        <div class="input-group">
                                            <input type="number" name="min_order" 
                                                   class="form-control @error('min_order') is-invalid @enderror"
                                                   value="{{ old('min_order', $supplierProduct->min_order ?? '') }}" 
                                                   min="1" step="1">
                                            <span class="input-group-text unit-display">
                                                {{ $supplierProduct->item->unit ?? '' }}
                                            </span>
                                        </div>
                                        @error('min_order')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Lead Time (hari)</label>
                                        <input type="number" name="lead_time" 
                                               class="form-control @error('lead_time') is-invalid @enderror"
                                               value="{{ old('lead_time', $supplierProduct->lead_time ?? '') }}" 
                                               min="1" step="1">
                                        @error('lead_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="text-end">
                                <a href="{{ route('supplier-products.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.querySelector('select[name="item_id"]').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const unit = selectedOption.dataset.unit || '';
            document.querySelector('.unit-display').textContent = unit;
        });
    </script>
    @endpush
</x-layout>
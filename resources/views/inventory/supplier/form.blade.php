<x-layout>
  <x-slot:title>{{ $type == 'create' ? 'Tambah' : 'Ubah' }} Supplier</x-slot:title>

  <div class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <a class="btn btn-warning float-end rounded-2" href="{{ route('supplier.index') }}">Kembali</a>
        </div>
        <div class="card-body">
          <form
            action="{{ $type == 'create' ? route('supplier.store') : ($supplier ? route('supplier.update', $supplier->id) : '') }}"
            method="POST">
            @csrf
            @isset($supplier)
              @method('PUT')
              @endif
              <div class="form-group">
                <label for="name">Nama Supplier <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                  name="name"
                  value="{{ old('name') ? old('name') : (isset($supplier) ? $supplier->name : old('name')) }}"
                  autocomplete="off" autofocus required>
                @error('name')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
              <div class="form-group">
                <label for="phone">No. Telp <span class="text-danger">*</span></label>
                <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone"
                  name="phone"
                  value="{{ old('phone') ? old('phone') : (isset($supplier) ? $supplier->phone : old('phone')) }}"
                  autocomplete="off" required>
                @error('phone')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                  name="email"
                  value="{{ old('email') ? old('email') : (isset($supplier) ? $supplier->email : old('email')) }}"
                  autocomplete="off">
                @error('email')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
              <div class="form-group">
                <label for="address">Alamat <span class="text-danger">*</span></label>
                <input type="address" class="form-control @error('address') is-invalid @enderror" id="address"
                  name="address"
                  value="{{ old('address') ? old('address') : (isset($supplier) ? $supplier->address : old('address')) }}"
                  autocomplete="off" required>
                @error('address')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
              <div class="mb-4">
    <label class="font-bold">Produk yang Disediakan</label>
    <div id="product-container">
        <input type="text" name="products[]" placeholder="Nama Produk" class="w-full mb-2">
    </div>
    <button type="button" onclick="addProductInput()" class="bg-gray-400 text-white px-2 py-1 rounded">+ Produk</button>
</div>

<script>
function addProductInput() {
    const container = document.getElementById('product-container');
    const input = document.createElement('input');
    input.type = 'text';
    input.name = 'products[]';
    input.placeholder = 'Nama Produk';
    input.classList.add('w-full', 'mb-2');
    container.appendChild(input);
}
</script>
              <div class="form-group">
                <label for="description">Deskripsi</label>
                <input type="text" class="form-control @error('description') is-invalid @enderror" id="description"
                  name="description"
                  value="{{ old('description') ? old('description') : (isset($supplier) ? $supplier->description : old('description')) }}"
                  autocomplete="off">
                @error('description')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
              <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </x-layout>

<x-layout>
  <x-slot:title>{{ $type == 'create' ? 'Tambah' : 'Ubah' }} Barang</x-slot:title>

  <div class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <a class="btn btn-warning float-end rounded-2" href="{{ route('item.index') }}">Kembali</a>
        </div>
        <div class="card-body">
          <form id="form-item"
            action="{{ $type == 'create' ? route('item.store') : ($item ? route('item.update', $item->id) : '') }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            @isset($item)
              @method('PUT')
              @endif
              <div class="form-group">
                <label for="code">Kode Barang <span class="text-danger">*</span></label>
                <div class="input-group">
                  <input type="text" class="form-control @error('code') is-invalid @enderror" id="code"
                    name="code" value="{{ old('code') ? old('code') : (isset($item) ? $item->code : old('code')) }}"
                    autocomplete="off" required>
                  @error('code')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                  <div class="input-group-append">
                    <button type="button" id="generate_code" class="input-group-text btn btn-primary text-white">Buat
                      Kode
                    </button>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="name">Nama Barang <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                  name="name" value="{{ old('name') ? old('name') : (isset($item) ? $item->name : old('name')) }}"
                  autocomplete="off" autofocus required>
                @error('name')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
              <label for="category">Kategori Barang <span class="text-danger">*</span></label>
              @php
                $category_id = old('category')
                    ? old('category')
                    : (isset($item)
                        ? $item->category->id
                        : old('category'));
              @endphp

              <div class="form-group">
                <div class="input-group">
                  <select class="form-select @error('category') is-invalid @enderror" id="invalid-is-invalid-single-field"
                    aria-label="Category select" data-placeholder="Pilih Kategori" name="category" required>
                    <option>Pilih Kategori</option>
                    @foreach ($categories as $category)
                      <option value="{{ $category->id }}" {{ $category->id == $category_id ? 'selected' : '' }}>
                        {{ $category->name }}
                      </option>
                    @endforeach
                  </select>
                  @error('category')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                  <button class="btn btn-primary text-white" type="button" id="create-category-btn">Buat
                    Kategori</button>
                </div>
              </div>

              <div class="form-group">
                <label for="cost_price">Harga Modal <span class="text-danger">*</span></label>
                <input type="number" class="form-control @error('cost_price') is-invalid @enderror" id="cost_price"
                  min="1" name="cost_price"
                  value="{{ old('cost_price') ? old('cost_price') : (isset($item) ? $item->cost_price : old('cost_price')) }}"
                  autocomplete="off">
                @error('cost_price')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
              <div class="form-group">
                <div class="my-2">
                  <label>Harga Jual <span class="text-danger">*</span></label>
                  <button type="button" id="add-wholesale-price"
                    class="float-end btn btn-xs btn-primary fw-bold text-white">Tambah Harga Grosir
                  </button>
                </div>
                <div class="mb-2">
                  <input type="number" class="form-control @error('selling_price') is-invalid @enderror"
                    id="selling_price" min="1" name="selling_price"
                    value="{{ old('selling_price') ? old('selling_price') : (isset($item) ? $item->selling_price : old('selling_price')) }}"
                    autocomplete="off">
                  @error('selling_price')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
                @if ($type == 'edit')
                  @foreach ($item->wholesalePrices as $wholesalePrice)
                    <div class="input-group mt-2">
                      <input type="number" class="form-control wholesale_price" name="wholesale_price[]"
                        value="{{ $wholesalePrice->price }}" required placeholder="Harga Grosir">
                      <input type="number" class="form-control min_qty" name="min_qty[]" min="1"
                        value="{{ $wholesalePrice->min_qty }}" placeholder="Min" required>
                      <button type="button" class="btn btn-danger text-white remove-wholesale-price">Hapus</button>
                    </div>
                  @endforeach
                @endif
                <div id="wholesale_price_wrapper"></div>
              </div>
              <div class="form-group">
                <label for="stock">Stok <span class="text-danger">*</span></label>
                <input type="number" min="0" class="form-control @error('stock') is-invalid @enderror"
                  id="stock" name="stock"
                  value="{{ old('stock') ? old('stock') : (isset($item) ? $item->stock : old('stock')) }}"
                  autocomplete="off" required>
                @error('stock')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
              <div class="row mb-3">
                <div class="col-md-3">
                  <img src="/assets/images/items/{{ isset($item) ? $item->picture : 'default.png' }}" width="100"
                    id="img-preview" alt="Foto" />
                </div>
                <div class="col-md-9">
                  <div class="form-group">
                    <label for="picture" class="form-label">Foto <span class="text-muted"> (kosongkan jika tidak ingin
                        diubah)</span></label>
                    <input class="form-control @error('picture') is-invalid @enderror" type="file" id="picture"
                      name="picture">
                    @error('picture')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                </div>
              </div>
              <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <script>
      $(document).ready(function() {
        function generateCode() {
          const letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
          const numbers = '0123456789';
          let code = '';
          for (let i = 0; i < 4; i++) {
            code += letters.charAt(Math.floor(Math.random() * letters.length));
          }
          for (let i = 0; i < 4; i++) {
            code += numbers.charAt(Math.floor(Math.random() * numbers.length));
          }
          return code;
        }

        function addWholesalePrice() {
          removeEvent();

          const wholesalePriceInput = `
            <div class="input-group mt-2">
              <input type="number" class="form-control wholesale_price" name="wholesale_price[]" required placeholder="Harga Grosir">
              <input type="number" class="form-control min_qty" name="min_qty[]" min="1" placeholder="Min" required>
              <button type="button" class="btn btn-danger text-white remove-wholesale-price">Hapus</button>
            </div>
          `;
          $('#wholesale_price_wrapper').append(wholesalePriceInput);

          addEvent();
        }

        addEvent();

        function addEvent() {
          $('.min_qty').keydown(function(e) {
            if (e.ctrlKey && e.keyCode === 13) addWholesalePrice();
          });

          $('.wholesale_price').keydown(function(e) {
            if (e.ctrlKey && e.keyCode === 13) addWholesalePrice();
          });
        }

        function removeEvent() {
          $('.min_qty').off('keydown');
          $('.wholesale_price').off('keydown');
        }

        const type = '{{ $type }}';

        $('#picture').change(function() {
          const file = $(this)[0].files[0];
          const fileReader = new FileReader();
          fileReader.onload = function(e) {
            $('#img-preview').attr('src', e.target.result);
          }
          fileReader.readAsDataURL(file);
        });

        if (type === 'create') {
          $('#code').val(generateCode());
        }

        $('#generate_code').click(function() {
          $('#code').val(generateCode());
        });

        $('#create-category-btn').click(function() {
          const category = prompt('Masukkan nama kategori baru');
          if (category) {
            $.ajax({
              url: '{{ route('category.store') }}',
              method: 'POST',
              data: {
                _token: '{{ csrf_token() }}',
                name: category,
              },
              success: function(response) {
                const option = `<option value="${response.id}" selected>${response.name}</option>`;
                $('#invalid-is-invalid-single-field').append(option);
              }
            });
          }
        });

        $('#add-wholesale-price').click(addWholesalePrice);

        $('#selling_price').keydown(function(e) {
          if (e.ctrlKey && e.keyCode === 13) addWholesalePrice();
        });

        $(document).on('click', '.remove-wholesale-price', function() {
          $(this).parent().remove();
        });

        $('#form-item').submit(function(e) {
          const selling_price = parseInt($('#selling_price').val());
          const cost_price = parseInt($('#cost_price').val());

          if (selling_price <= cost_price) {
            alert('Harga jual tidak boleh kurang dari atau sama dengan harga modal');
            e.preventDefault();
            return;
          }

          const minQty = $('.min_qty');

          const min_qty = [];
          for (let i = 0; i < minQty.length; i++) {
            const qty = parseInt(minQty[i].value);

            if (qty === 0) {
              alert('Minimal pembelian tidak boleh kosong');
              e.preventDefault();
              return;
            } else if (qty <= 0) {
              alert('Minimal pembelian tidak boleh kurang dari atau sama dengan 0');
              e.preventDefault();
              return;
            } else if (min_qty.includes(qty)) {
              alert('Minimal pembelian tidak boleh sama');
              e.preventDefault();
              return;
            }

            min_qty.push(qty);
          }

          const sellingPrices = $('.wholesale_price');
          for (let i = 0; i < sellingPrices.length; i++) {
            const sellingPrice = parseInt(sellingPrices[i].value);
            if (sellingPrice === 0) {
              alert('Harga jual tidak boleh kosong');
              e.preventDefault();
              return;
            } else if (sellingPrice <= 0) {
              alert('Harga jual tidak boleh kurang dari atau sama dengan 0');
              e.preventDefault();
              return;
            } else if (sellingPrice <= cost_price) {
              alert('Harga jual tidak boleh kurang dari atau sama dengan harga modal');
              e.preventDefault();
              return;
            }
          }
        });
      });
    </script>
  </x-layout>

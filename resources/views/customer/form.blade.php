<x-layout>
  <x-slot:title>{{ $type == 'create' ? 'Tambah' : 'Ubah' }} Pelanggan</x-slot:title>

  <div class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <a class="btn btn-warning float-end rounded-2" href="{{ route('customer.index') }}">Kembali</a>
        </div>
        <div class="card-body">
          <form
            action="{{ $type == 'create' ? route('customer.store') : ($customer ? route('customer.update', $customer->id) : '') }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            @isset($customer)
              @method('PUT')
              @endif
              <div class="form-group">
                <label for="name">Nama Pelanggan <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                  name="name"
                  value="{{ old('name') ? old('name') : (isset($customer) ? $customer->name : old('name')) }}"
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
                  value="{{ old('phone') ? old('phone') : (isset($customer) ? $customer->phone : old('phone')) }}"
                  autocomplete="off" required>
                @error('phone')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
              <div class="form-group">
                <label for="position">Alamat</label>
                <input type="string" class="form-control @error('address') is-invalid @enderror" id="address"
                  name="address"
                  value="{{ old('address') ? old('address') : (isset($customer) ? $customer->address : old('address')) }}"
                  autocomplete="off">
                @error('address')
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

    <script>
      $(document).ready(function() {
        $('#picture').change(function() {
          const file = $(this)[0].files[0];
          const fileReader = new FileReader();
          fileReader.onload = function(e) {
            $('#img-preview').attr('src', e.target.result);
          }
          fileReader.readAsDataURL(file);
        });
      });
    </script>
  </x-layout>

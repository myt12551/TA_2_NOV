<x-layout>
  <x-slot:title>{{ $type == 'create' ? 'Tambah' : 'Ubah' }} Pegawai</x-slot:title>

  <div class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <a class="btn btn-warning float-end rounded-2" href="{{ route('user.index') }}">Kembali</a>
        </div>
        <div class="card-body">
          <form action="{{ $type == 'create' ? route('user.store') : ($user ? route('user.update', $user->id) : '') }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            @isset($user)
              @method('PUT')
              @endif
              <div class="form-group">
                <label for="name">Nama Pegawai <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                  name="name" value="{{ old('name') ? old('name') : (isset($user) ? $user->name : old('name')) }}"
                  autocomplete="off" autofocus required>
                @error('name')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
              <div class="form-group">
                <label for="username">Username <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('username') is-invalid @enderror" id="username"
                  name="username"
                  value="{{ old('username') ? old('username') : (isset($user) ? $user->username : old('username')) }}"
                  autocomplete="off" required>
                @error('username')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
              <div class="form-group">
                <label for="email">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                  name="email" value="{{ old('email') ? old('email') : (isset($user) ? $user->email : old('email')) }}"
                  autocomplete="off" required>
                @error('email')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
              <div class="form-group">
                <label for="phone">No. Telp <span class="text-danger">*</span></label>
                <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone"
                  name="phone" value="{{ old('phone') ? old('phone') : (isset($user) ? $user->phone : old('phone')) }}"
                  autocomplete="off" required>
                @error('phone')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
              <div class="form-group">
                <label for="position">Jabatan</label>
                <input type="string" class="form-control @error('position') is-invalid @enderror" id="position"
                  name="position"
                  value="{{ old('position') ? old('position') : (isset($user) ? $user->position : old('position')) }}"
                  autocomplete="off">
                @error('position')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
              <label for="role">Hak Akses <span class="text-danger">*</span></label>
              <div class="form-group">
                @php
                  $role = old('role') ? old('role') : (isset($user) ? $user->role : old('role'));
                @endphp

                <select id="invalid-is-invalid-single-field" style="width: 100%;"
                  class="form-select @error('role') is-invalid @enderror" aria-label="Role select"
                  data-placeholder="Pilih Hak Akses" name="role" required>
                  <option>Pilih Hak Akses</option>
                  <option value="supervisor" {{ $role == 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                  <option value="admin" {{ $role == 'admin' ? 'selected' : '' }}>Admin</option>
                  <option value="cashier" {{ $role == 'cashier' ? 'selected' : '' }}>Kasir</option>
                </select>
                @error('role')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
              <div class="form-group">
                <label for="password">
                  Password @if ($type == 'create')<span
                    class="text-danger">*</span>@else<span class="text-muted"> (kosongkan jika tidak ingin
                      diubah)</span>
                  @endif
                </label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                  name="password" autocomplete="off">
                @error('password')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
              <div class="form-group">
              <label for="password_confirmation">Konfirmasi Password @if ($type == 'create')<span class="text-danger">*</span>@else<span
                      class="text-muted"> (kosongkan jika tidak ingin
                      diubah)</span>@endif
                </label>
                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                  id="password_confirmation" name="password_confirmation" autocomplete="off">
                @error('password_confirmation')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
              <div class="row mb-3">
                <div class="col-md-3">
                  <img src="/assets/images/users/{{ isset($user) ? $user->picture : 'profile.jpg' }}" width="100"
                    id="img-preview" alt="Foto" />
                </div>
                <div class="col-md-9">
                  <div class="form-group">
                    <label for="picture" class="form-label">Foto <span class="text-muted"> (kosongkan jika tidak ingin
                        diubah)</span></label>
                    <input class="form-control @error('picture') is-invalid @enderror" type="file" id="picture"
                      name="picture">
                    <small class="text-danger">Pastikan foto berbentuk persegi <b>1:1</b></small>
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

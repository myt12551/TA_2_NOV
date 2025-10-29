<x-layout>
  <x-slot:title>Ubah Profil</x-slot:title>

  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-header">
          <a class="btn btn-warning float-end rounded-2" href="/" tabindex="1">Kembali</a>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('profile.update', $user) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group row">
              <label for="name" class="col-md-4 col-form-label text-md-right">Nama <span
                  class="text-danger">*</span></label>
              <div class="col-md-8">
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                  name="name" value="{{ old('name') ? old('name') : $user->name }}" required autocomplete="name"
                  autofocus>
                @error('name')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>
            <div class="form-group row">
              <label for="username" class="col-md-4 col-form-label text-md-right">Username <span
                  class="text-danger">*</span></label>
              <div class="col-md-8">
                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror"
                  name="username" value="{{ old('username') ? old('username') : $user->username }}" required
                  autocomplete="username" autofocus>
                @error('username')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>
            <div class="form-group row">
              <label for="email" class="col-md-4 col-form-label text-md-right">Email <span
                  class="text-danger">*</span></label>
              <div class="col-md-8">
                <input id="email" type="text" class="form-control @error('email') is-invalid @enderror"
                  name="email" value="{{ old('email') ? old('email') : $user->email }}" required autocomplete="email"
                  autofocus>
                @error('email')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>
            <div class="form-group row">
              <label for="phone" class="col-md-4 col-form-label text-md-right">No. Telp <span
                  class="text-danger">*</span></label>
              <div class="col-md-8">
                <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror"
                  name="phone" value="{{ old('phone') ? old('phone') : $user->phone }}" required autocomplete="phone"
                  autofocus>
                @error('phone')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>
            <div class="form-group row">
              <label for="position" class="col-md-4 col-form-label text-md-right">Jabatan</label>
              <div class="col-md-8">
                <input id="position" type="text" class="form-control @error('position') is-invalid @enderror"
                  name="position" value="{{ old('position') ? old('position') : $user->position }}"
                  autocomplete="position" autofocus>
                @error('position')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>
            <div class="form-group row">
              <label for="password" class="col-md-4 col-form-label text-md-right">
                <div>Foto</div><span class="text-muted"> kosongkan jika tidak ingin
                  diubah</span>
              </label>
              <div class="col-md-2">
                <img src="/assets/images/users/{{ $user->picture }}" alt="Foto" id="img-preview" width="120">
              </div>
              <div class="col-md-6">
                <input type="file" name="picture" class="form-control @error('picture') is-invalid @enderror"
                  id="picture">
                <small class="text-danger">Pastikan foto berbentuk persegi <b>1:1</b></small>
                @error('picture')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>
            <div class="form-group row mb-0">
              <div class="col-md-8 offset-md-4">
                <button type="submit" class="btn btn-primary">
                  Simpan
                </button>
              </div>
            </div>
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

<x-layout>
  <x-slot:title>Profil Saya</x-slot:title>

  <div class="row">
    @if (session('status'))
      <x-alert type="success" :message="session('status')"></x-alert>
    @endif
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-2">
              <img src="/assets/images/users/{{ $user->picture }}" class="img-fluid rounded-3" alt=""
                style="width: 100% !important;">
            </div>
            <div class="col-md-5">
              <div class="form-group row">
                <label for="" class="col-md-4 col-form-label"><b>Nama</b></label>
                <div class="col-md-8">
                  <input type="text" class="form-control" value="{{ $user->name }}" readonly>
                </div>
              </div>
              <div class="form-group row mt-4">
                <label for="" class="col-md-4 col-form-label"><b>Username</b></label>
                <div class="col-md-8">
                  <input type="text" class="form-control" value="{{ $user->username }}" readonly>
                </div>
              </div>
              <div class="form-group row mt-4">
                <label for="" class="col-md-4 col-form-label"><b>Email</b></label>
                <div class="col-md-8">
                  <input type="email" class="form-control" value="{{ $user->email }}" readonly>
                </div>
              </div>
            </div>
            <div class="col-md-5">
              <div class="form-group row">
                <label for="" class="col-md-4 col-form-label"><b>No. Telp</b></label>
                <div class="col-md-8">
                  <input type="tel" class="form-control" value="{{ $user->phone }}" readonly>
                </div>
              </div>
              <div class="form-group row mt-4">
                <label for="" class="col-md-4 col-form-label"><b>Jabatan</b></label>
                <div class="col-md-8">
                  <input type="text" class="form-control" value="{{ $user->position }}" readonly>
                </div>
              </div>
              <div class="form-group row mt-4">
                <label for="" class="col-md-4 col-form-label"><b>Hak Akses</b></label>
                <div class="col-md-8">
                  <input type="text" class="form-control"
                    value="{{ $user->role == 'cashier' ? 'Kasir' : Str::ucfirst($user->role) }}" readonly>
                </div>
              </div>
            </div>
          </div>
          <div class="float-end">
            <a href="{{ route('profile.edit') }}" class="btn btn-primary rounded-3">
              Ubah
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-layout>

<table class="table" id="user_table">
  <thead class="text-primary">
    <tr>
      <th><b>No</b></th>
      <th><b>Nama</b></th>
      <th><b>Username</b></th>
      <th><b>Hak Akses</b></th>
      <th><b>Jabatan</b></th>
      @if ($type != 'export')
        <th class="text-center"><b>Aksi</b></th>
      @endif
    </tr>
  </thead>
  <tbody>
    @foreach ($users as $user)
      <tr>
        <td style="width: 10%;">{{ $loop->iteration }}</td>
        <td>{{ $user->name }}</td>
        <td>{{ $user->username }}</td>
        <td>{{ $user->role == 'cashier' ? 'Kasir' : ($user->role == 'superadmin' ? 'Super Admin' : 'Admin') }}</td>
        <td>{{ $user->position ? $user->position : '-' }}</td>
        @if ($type != 'export')
          <td style="width: 25%;" class="text-center">
            <button class="btn btn-sm rounded-3 text-white btn-secondary" data-bs-toggle="modal"
              data-bs-target="#detail-modal" data-name="{{ $user->name }}" data-username="{{ $user->username }}"
              data-phone="{{ $user->phone }}" data-picture="{{ $user->picture }}" data-role="{{ $user->role }}"
              data-email="{{ $user->email }}" data-position="{{ $user->position ? $user->position : '-' }}"
              id="detail-btn">
              <i class="fas fa-info-circle"></i> Detil
            </button>
            <a href="{{ route('user.edit', $user->id) }}" class="btn btn-sm rounded-3 text-white btn-success">
              <i class="fas fa-edit"></i>
              Ubah
            </a>
            <form action="{{ route('user.destroy', $user->id) }}" method="post" class="d-inline">
              <button type="submit"
                onclick="return confirm('Apakah anda yakin ingin menghapus user {{ $user->name }}?')"
                class="btn btn-sm rounded-3 text-white btn-danger">
                <i class="fas fa-trash-alt"></i>
                Hapus
              </button>
              @csrf
              @method('DELETE')
            </form>
          </td>
        @endif
      </tr>
    @endforeach
  </tbody>
</table>

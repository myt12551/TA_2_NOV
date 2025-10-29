<table class="table" id="absence_table">
  <thead class="text-primary">
    <tr>
      <th><b>No</b></th>
      <th><b>Tanggal</b></th>
      <th><b>Nama Pegawai</b></th>
      <th><b>Masuk</b></th>
      <th><b>Keluar</b></th>
      @if ($type != 'export')
        <th class="text-center"><b>Aksi</b></th>
      @endif
    </tr>
  </thead>
  <tbody>
    @foreach ($absences as $absence)
      <tr>
        <td style="width: 10%;">{{ $loop->iteration }}</td>
        <td>{{ $absence->created_at->locale('id')->isoFormat('dddd, D MMMM Y') }}</td>
        <td>{{ $absence->user->name }}</td>
        <td>{{ $absence->login_at }}</td>
        <td>{{ $absence->logout_at ? $absence->logout_at : '-' }}</td>
        @if ($type != 'export')
          <td style="width: 25%;" class="text-center">
            <button class="btn btn-sm rounded-3 text-white btn-secondary" data-bs-toggle="modal"
              data-bs-target="#detail-modal" data-name="{{ $absence->user->name }}"
              data-login-at="{{ $absence->login_at }}"
              data-logout-at="{{ $absence->logout_at ? $absence->logout_at : '-' }}"
              data-created-at="{{ $absence->created_at->locale('id')->isoFormat('dddd, D MMMM Y') }}" id="detail-btn">
              <i class="fas fa-info-circle"></i> Detil
            </button>
          </td>
        @endif
      </tr>
    @endforeach
  </tbody>
</table>

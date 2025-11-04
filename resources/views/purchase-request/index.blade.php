<x-layout>
    <x-slot:title>Daftar Permintaan Pembelian</x-slot:title>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Daftar Permintaan Pembelian</h4>
                        <a href="{{ route('purchase-requests.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Buat PR Baru
                        </a>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nomor PR</th>
                                        <th>Tanggal</th>
                                        <th>Supplier</th>
                                        <th>Status</th>
                                        <th>Diminta Oleh</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($requests as $pr)
                                        <tr>
                                            <td>{{ $pr->pr_number }}</td>
                                            <td>{{ $pr->request_date->format('d/m/Y') }}</td>
                                            <td>{{ $pr->supplier->name }}</td>
                                            <td>
                                                @if($pr->status === 'pending')
                                                    <span class="badge bg-warning">Menunggu</span>
                                                @elseif($pr->status === 'approved')
                                                    <span class="badge bg-success">Disetujui</span>
                                                @else
                                                    <span class="badge bg-danger">Ditolak</span>
                                                @endif
                                            </td>
                                            <td>{{ $pr->requester->name }}</td>
                                            <td>
                                                <a href="{{ route('purchase-requests.show', $pr->id) }}" 
                                                   class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Tidak ada data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
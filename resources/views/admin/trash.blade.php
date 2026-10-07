@extends('admin.layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin /</span> Sampah (Recycle Bin)</h4>

    @include('admin.layout.alert')

    <div class="nav-align-top mb-4">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pasien" aria-controls="navs-pasien" aria-selected="true">
                    Pasien ({{ count($deletedPasien) }})
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-dokter" aria-controls="navs-dokter" aria-selected="false">
                    Dokter ({{ count($deletedDokter) }})
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-galeri" aria-controls="navs-galeri" aria-selected="false">
                    Galeri ({{ count($deletedGaleri) }})
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-kegiatan" aria-controls="navs-kegiatan" aria-selected="false">
                    Kegiatan ({{ count($deletedKegiatan) }})
                </button>
            </li>
        </ul>
        <div class="tab-content">
            <!-- Tab Pasien -->
            <div class="tab-pane fade show active" id="navs-pasien" role="tabpanel">
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Pasien</th>
                                <th>Email</th>
                                <th>Tgl Janji</th>
                                <th>Dihapus Pada</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($deletedPasien as $p)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ $p->nama_pasien }}</strong></td>
                                <td>{{ $p->email_pasien }}</td>
                                <td>{{ $p->tanggal_janji }}</td>
                                <td>{{ $p->deleted_at }}</td>
                                <td>
                                    <form action="/admin-area/trash/restore/pasien/{{ encrypt($p->id_pasien) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success"><i class="bx bx-undo"></i> Pulihkan</button>
                                    </form>
                                    <form action="/admin-area/trash/force-delete/pasien/{{ encrypt($p->id_pasien) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus permanen pasien ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bx bx-trash"></i> Hapus Permanen</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Tidak ada data pasien di sampah.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab Dokter -->
            <div class="tab-pane fade" id="navs-dokter" role="tabpanel">
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Dokter</th>
                                <th>SIP</th>
                                <th>Dihapus Pada</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($deletedDokter as $d)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ $d->nama_dokter }}</strong></td>
                                <td>{{ $d->sip_dokter }}</td>
                                <td>{{ $d->deleted_at }}</td>
                                <td>
                                    <form action="/admin-area/trash/restore/dokter/{{ encrypt($d->id_dokter) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success"><i class="bx bx-undo"></i> Pulihkan</button>
                                    </form>
                                    <form action="/admin-area/trash/force-delete/dokter/{{ encrypt($d->id_dokter) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus permanen dokter ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bx bx-trash"></i> Hapus Permanen</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Tidak ada data dokter di sampah.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab Galeri -->
            <div class="tab-pane fade" id="navs-galeri" role="tabpanel">
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Judul Foto</th>
                                <th>Dihapus Pada</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($deletedGaleri as $g)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ $g->judul }}</strong></td>
                                <td>{{ $g->deleted_at }}</td>
                                <td>
                                    <form action="/admin-area/trash/restore/galeri/{{ encrypt($g->id_galeri) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success"><i class="bx bx-undo"></i> Pulihkan</button>
                                    </form>
                                    <form action="/admin-area/trash/force-delete/galeri/{{ encrypt($g->id_galeri) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus permanen galeri ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bx bx-trash"></i> Hapus Permanen</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Tidak ada foto galeri di sampah.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab Kegiatan -->
            <div class="tab-pane fade" id="navs-kegiatan" role="tabpanel">
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Judul Kegiatan</th>
                                <th>Tgl Kegiatan</th>
                                <th>Dihapus Pada</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($deletedKegiatan as $k)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ $k->judul_kegiatan }}</strong></td>
                                <td>{{ $k->tgl_kegiatan }}</td>
                                <td>{{ $k->deleted_at }}</td>
                                <td>
                                    <form action="/admin-area/trash/restore/kegiatan/{{ encrypt($k->id_kegiatan) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success"><i class="bx bx-undo"></i> Pulihkan</button>
                                    </form>
                                    <form action="/admin-area/trash/force-delete/kegiatan/{{ encrypt($k->id_kegiatan) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus permanen kegiatan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bx bx-trash"></i> Hapus Permanen</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Tidak ada agenda kegiatan di sampah.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

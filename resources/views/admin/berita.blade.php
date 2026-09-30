@extends('admin.layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light"><a href="/admin-area" class="a-breadcrumbs">Beranda</a> /</span>
        Manajemen Berita & Artikel
    </h4>

    @include('admin.layout.alert')

    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <a href="/admin-area/berita/new" class="btn btn-primary btn-sm">
                <i class="bx bx-plus me-1"></i> Tambah Artikel Baru
            </a>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Status</th>
                        <th>Tgl Terbit</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($beritas as $b)
                    <tr>
                        <td><small class="text-muted">{{ $b->id_berita }}</small></td>
                        <td>
                            <strong>{{ Str::limit($b->judul, 50) }}</strong>
                            @if($b->images)
                            <br><small class="text-muted"><i class="bx bx-image me-1"></i>Ada gambar</small>
                            @endif
                        </td>
                        <td>{{ $b->penulis ?? '-' }}</td>
                        <td>
                            @if($b->status === 'published')
                            <span class="badge bg-label-success">Published</span>
                            @else
                            <span class="badge bg-label-secondary">Draft</span>
                            @endif
                        </td>
                        <td>{{ $b->tgl_terbit ? $b->tgl_terbit->format('d M Y') : '-' }}</td>
                        <td>
                            <a href="/admin-area/berita/toggle/{{ Crypt::encrypt($b->id_berita) }}"
                               class="btn btn-sm btn-outline-{{ $b->status === 'published' ? 'secondary' : 'success' }} me-1"
                               title="{{ $b->status === 'published' ? 'Jadikan Draft' : 'Publish' }}">
                                <i class="bx bx-{{ $b->status === 'published' ? 'hide' : 'show' }}"></i>
                            </a>
                            <a href="/admin-area/berita/edit/{{ Crypt::encrypt($b->id_berita) }}"
                               class="btn btn-sm btn-outline-primary me-1">
                                <i class="bx bx-edit-alt"></i>
                            </a>
                            <a href="/admin-area/berita/delete/{{ Crypt::encrypt($b->id_berita) }}"
                               onclick="return confirm('Hapus artikel \'{{ $b->judul }}\'?')"
                               class="btn btn-sm btn-outline-danger">
                                <i class="bx bx-trash"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            <i class="bx bx-news fs-3 d-block mb-2"></i>
                            Belum ada artikel yang ditambahkan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $beritas->links('admin.layout.pagination') }}
        </div>
    </div>
</div>
@endsection

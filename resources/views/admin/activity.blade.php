@extends('admin.layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><a href="/admin-area" class="a-breadcrumbs">Beranda</a> /</span> Agenda Kegiatan Klinik</h4>

    @include('admin.layout.alert')
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <a href="/admin-area/kegiatan/new" class="btn btn-primary btn-sm"><i class="bx bx-plus me-1"></i> Tambah Kegiatan Baru</a>
        </div>
    </div>

    <div class="row mb-5">
        @if (count($activities) == 0 || Session::has('message')) 
        <div class="card mb-4 text-center">
            <h5 class="card-header py-5 text-muted">Belum ada agenda kegiatan yang ditambahkan.</h5>
        </div>
        @else 
        @foreach ($activities as $data)
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100 shadow-sm">
                <img class="card-img-top" src="{{ asset('img/activity/'.$data->images) }}" alt="{{ $data->judul_kegiatan }}" style="height: 180px; object-fit: cover;" />
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-bold text-dark mb-1">{{ $data->judul_kegiatan }}</h5>
                    <small class="text-muted mb-2"><i class="bx bx-calendar me-1"></i> {{ \Carbon\Carbon::parse($data->tgl_kegiatan)->format('d M Y') }}</small>
                    <p class="card-text text-muted flex-grow-1">
                        {{ Str::limit(strip_tags($data->deskripsi_kegiatan), 80) }}
                    </p>
                    <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                        <a href="/admin-area/kegiatan/edit/{{ Crypt::encrypt($data->id_kegiatan) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bx bx-edit-alt"></i> Edit
                        </a>
                        <form action="/admin-area/kegiatan/delete/{{ Crypt::encrypt($data->id_kegiatan) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kegiatan {{ $data->judul_kegiatan }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bx bx-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        <div class="col-12 mt-3">
            {{ $activities->links('admin.layout.pagination') }}
        </div>
        @endif
    </div>
</div>
@endsection
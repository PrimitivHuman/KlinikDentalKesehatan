@extends('admin.layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light"><a href="/admin-area" class="a-breadcrumbs">Beranda</a> /</span>
        Manajemen Layanan Klinik
    </h4>

    @include('admin.layout.alert')

    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <a href="/admin-area/layanan/new" class="btn btn-primary btn-sm">
                <i class="bx bx-plus me-1"></i> Tambah Layanan Baru
            </a>
        </div>
    </div>

    <div class="row mb-5">
        @forelse ($layanans as $l)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm {{ !$l->aktif ? 'opacity-50' : '' }}">
                @if($l->images)
                <img class="card-img-top" src="{{ asset('img/layanan/'.$l->images) }}"
                     alt="{{ $l->nama_layanan }}" style="height: 160px; object-fit: cover;">
                @else
                <div class="card-img-top d-flex align-items-center justify-content-center bg-label-primary"
                     style="height: 100px;">
                    <span class="bx {{ $l->ikon ?? 'bx-plus-medical' }} fs-1 text-primary"></span>
                </div>
                @endif
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <h6 class="card-title fw-bold mb-0">{{ $l->nama_layanan }}</h6>
                        @if($l->aktif)
                        <span class="badge bg-label-success ms-2">Aktif</span>
                        @else
                        <span class="badge bg-label-secondary ms-2">Nonaktif</span>
                        @endif
                    </div>
                    <small class="text-muted mb-1">
                        @if($l->durasi)<i class="bx bx-time me-1"></i>{{ $l->durasi }} @endif
                    </small>
                    <small class="text-primary fw-semibold mb-2">{{ $l->harga_range }}</small>
                    <p class="card-text text-muted flex-grow-1" style="font-size: 13px;">
                        {{ Str::limit(strip_tags($l->deskripsi), 80) }}
                    </p>
                    <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                        <form action="/admin-area/layanan/toggle/{{ Crypt::encrypt($l->id_layanan) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-{{ $l->aktif ? 'secondary' : 'success' }}" title="{{ $l->aktif ? 'Nonaktifkan' : 'Aktifkan' }}">
                                <i class="bx bx-{{ $l->aktif ? 'hide' : 'show' }}"></i>
                            </button>
                        </form>
                        <a href="/admin-area/layanan/edit/{{ Crypt::encrypt($l->id_layanan) }}"
                           class="btn btn-sm btn-outline-primary">
                            <i class="bx bx-edit-alt"></i> Edit
                        </a>
                        <form action="/admin-area/layanan/delete/{{ Crypt::encrypt($l->id_layanan) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus layanan \'{{ addslashes($l->nama_layanan) }}\'?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                <i class="bx bx-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card text-center">
                <h5 class="card-header py-5 text-muted">
                    <i class="bx bx-plus-medical fs-2 d-block mb-2"></i>
                    Belum ada layanan yang ditambahkan.
                </h5>
            </div>
        </div>
        @endforelse

        <div class="col-12 mt-3">
            {{ $layanans->links('admin.layout.pagination') }}
        </div>
    </div>
</div>
@endsection

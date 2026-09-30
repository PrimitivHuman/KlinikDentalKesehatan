@extends('admin.layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header & Breadcrumbs -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold mb-1 text-dark">Data Tim Dokter</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/admin-area" class="text-muted">Beranda</a></li>
                    <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page">Dokter</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2 align-items-center w-100 w-md-auto justify-content-end">
            <a href="/admin-area/dokter/new" class="btn btn-primary d-inline-flex align-items-center gap-1 shadow-sm">
                <i class="bx bx-plus fs-5"></i>
                <span>Tambah Dokter</span>
            </a>
        </div>
    </div>

    @include('admin.layout.alert')

    <!-- Search & Filter Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="/admin-area/dokter" method="POST" class="row g-2 align-items-center">
                @csrf
                <div class="col-12 col-md-5 col-lg-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted">
                            <i class="bx bx-search"></i>
                        </span>
                        <input type="text" id="cari" name="cari" class="form-control border-start-0 ps-0" 
                               placeholder="Cari nama, SIP, STR, atau jadwal..." 
                               value="{{ request('cari') }}" required>
                    </div>
                </div>
                <div class="col-auto">
                    <button class="btn btn-outline-primary" type="submit">Cari</button>
                    @if(request('cari') || Session::has('message'))
                        <a href="/admin-area/dokter" class="btn btn-outline-secondary ms-1">Reset</a>
                    @endif
                </div>
                @error('cari')
                <div class="col-12">
                    <small class="text-danger"><i class="bx bx-error-circle"></i> {{ $message }}</small>
                </div>
                @enderror
            </form>
        </div>
    </div>

    <!-- Doctor Cards Grid -->
    @if (count($dokter) === 0 || Session::has('message'))
    <div class="card border-0 shadow-sm py-5 text-center">
        <div class="card-body">
            <div class="mb-3 text-muted">
                <i class="bx bx-user-x display-4 text-warning opacity-75"></i>
            </div>
            <h5 class="fw-bold text-dark mb-2">Data Dokter Tidak Ditemukan</h5>
            <p class="text-muted mb-4 small">
                {{ Session::get('message') ?? 'Belum ada data dokter yang terdaftar dalam sistem.' }}
            </p>
            <div class="d-flex justify-content-center gap-2">
                @if(request('cari') || Session::has('message'))
                    <a href="/admin-area/dokter" class="btn btn-outline-secondary btn-sm">Tampilkan Semua Dokter</a>
                @endif
                <a href="/admin-area/dokter/new" class="btn btn-primary btn-sm">Tambah Dokter Baru</a>
            </div>
        </div>
    </div>
    @else
    <div class="row g-4 mb-4">
        @foreach ($dokter as $data)
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            <div class="card h-100 border-0 shadow-sm transition-card" style="border-radius: 14px; overflow: hidden;">
                <!-- Doctor Image & Status Header -->
                <div class="position-relative bg-light text-center p-3 border-bottom" style="min-height: 180px; display: flex; align-items: center; justify-content: center;">
                    @if(!empty($data->images) && file_exists(public_path('img/dokter/'.$data->images)))
                        <img src="{{ asset('img/dokter/'.$data->images) }}" 
                             alt="{{ $data->nama_dokter }}" 
                             class="rounded-circle shadow-sm object-fit-cover" 
                             style="width: 120px; height: 120px; border: 4px solid #ffffff;">
                    @else
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-white shadow-sm text-primary fw-bold"
                             style="width: 120px; height: 120px; font-size: 2.2rem; border: 4px solid #ffffff;">
                            {{ strtoupper(substr($data->nama_dokter ?? 'D', 0, 1)) }}
                        </div>
                    @endif
                    <span class="position-absolute top-0 end-0 m-3 badge bg-light text-primary border" style="font-size: 11px;">
                        {{ $data->id_dokter }}
                    </span>
                </div>

                <!-- Doctor Details -->
                <div class="card-body d-flex flex-column p-4">
                    <h5 class="fw-bold text-dark mb-1 text-truncate" title="{{ $data->nama_dokter }}">
                        {{ $data->nama_dokter }}
                    </h5>
                    <span class="text-primary small fw-semibold mb-3">Dokter Gigi Spesialis</span>

                    <div class="mb-3 d-flex flex-column gap-2 text-muted small">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bx bx-calendar text-primary mt-1"></i>
                            <span class="text-truncate-2">{{ $data->jadwal_dokter ?? '-' }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bx bx-phone text-primary"></i>
                            <span>{{ $data->no_hp_dokter ?? '-' }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 text-truncate" title="{{ $data->email_dokter }}">
                            <i class="bx bx-envelope text-primary"></i>
                            <span class="text-truncate">{{ $data->email_dokter ?? '-' }}</span>
                        </div>
                    </div>

                    <!-- STR & SIP Badges -->
                    <div class="mt-auto pt-3 border-top">
                        <div class="d-flex flex-column gap-1 mb-3">
                            <div class="d-flex justify-content-between text-muted" style="font-size: 11px;">
                                <span>No. STR:</span>
                                <span class="fw-semibold font-monospace text-dark">{{ $data->str_dokter ?? '-' }}</span>
                            </div>
                            <div class="d-flex justify-content-between text-muted" style="font-size: 11px;">
                                <span>No. SIP:</span>
                                <span class="fw-semibold font-monospace text-dark">{{ $data->sip_dokter ?? '-' }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2">
                            <a href="/admin-area/dokter/edit/{{ Crypt::encrypt($data->id_dokter) }}" 
                               class="btn btn-sm btn-outline-primary flex-grow-1 d-inline-flex align-items-center justify-content-center gap-1">
                                <i class="bx bx-edit-alt"></i> Edit
                            </a>
                            <a href="/admin-area/dokter/delete/{{ Crypt::encrypt($data->id_dokter) }}" 
                               onclick="return confirm('Apakah Anda yakin ingin menghapus data {{ addslashes($data->nama_dokter) }}?')" 
                               class="btn btn-sm btn-outline-danger d-inline-flex align-items-center justify-content-center"
                               title="Hapus Dokter">
                                <i class="bx bx-trash"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $dokter->links('admin.layout.pagination') }}
    </div>
    @endif
</div>
@endsection
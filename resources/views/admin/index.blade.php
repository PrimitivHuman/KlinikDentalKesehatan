@extends('admin.layout.main')
@section('content')
<!-- Content -->

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <!-- Welcome Card -->
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Selamat datang, {{ Auth::user()->name }} 🎉</h5>
                            <p class="mb-4">
                                Selamat bekerja! Berikut adalah ringkasan data operasional Klinik FAM Dental Care saat ini.
                            </p>
                        </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <img src="{{ asset('admin/img/illustrations/man-with-laptop-light.png') }}" height="140" alt="View Badge User" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Data -->
        <div class="col-12 col-lg-6 mb-4 order-1">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Status Data</h5>
                </div>
                <div class="card-body">
                    <ul class="p-0 m-0">
                        <li class="d-flex mb-4 pb-1">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="bx bx-task fs-3 text-primary"></span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Agenda Kegiatan</h6>
                                </div>
                                <div class="user-progress d-flex align-items-center gap-1">
                                    <h6 class="mb-0">{{ $kegiatan_count ?? 0 }}</h6>
                                    <span class="text-muted">Data</span>
                                    <a href="/admin-area/kegiatan" class="btn btn-sm btn-outline-primary">Open</a>
                                </div>
                            </div>
                        </li>

                        <li class="d-flex mb-4 pb-1">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="bx bx-info-circle fs-3 text-info"></span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Informasi Umum</h6>
                                </div>
                                <div class="user-progress d-flex align-items-center gap-1">
                                    <h6 class="mb-0">@if (($information_count ?? 0) > 0) Filled @else Empty @endif</h6>
                                    <a href="/admin-area/informasi-umum" class="btn btn-sm btn-outline-primary">Open</a>
                                </div>
                            </div>
                        </li>

                        <li class="d-flex mb-4 pb-1">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="bx bx-group fs-3 text-success"></span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Dokter</h6>
                                </div>
                                <div class="user-progress d-flex align-items-center gap-1">
                                    <h6 class="mb-0">{{ $dokter_count ?? 0 }}</h6>
                                    <span class="text-muted">Data</span>
                                    <a href="/admin-area/dokter" class="btn btn-sm btn-outline-primary">Open</a>
                                </div>
                            </div>
                        </li>

                        <li class="d-flex mb-4 pb-1">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="bx bx-images fs-3 text-warning"></span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Galeri Foto</h6>
                                </div>
                                <div class="user-progress d-flex align-items-center gap-1">
                                    <h6 class="mb-0">{{ $galeri_count ?? 0 }}</h6>
                                    <span class="text-muted">Photos</span>
                                    <a href="/admin-area/galeri" class="btn btn-sm btn-outline-primary">Open</a>
                                </div>
                            </div>
                        </li>

                        <li class="d-flex mb-4 pb-1">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="bx bxs-user-account fs-3 text-danger"></span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Pengguna</h6>
                                </div>
                                <div class="user-progress d-flex align-items-center gap-1">
                                    <h6 class="mb-0">{{ $user_count ?? 0 }}</h6>
                                    <span class="text-muted">Users</span>
                                    <a href="/admin-area/akun" class="btn btn-sm btn-outline-primary">Open</a>
                                </div>
                            </div>
                        </li>

                        <li class="d-flex mb-2 pb-1">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="bx bx-user-voice fs-3 text-secondary"></span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Data Pasien</h6>
                                </div>
                                <div class="user-progress d-flex align-items-center gap-1">
                                    <h6 class="mb-0">{{ $pasien_count ?? 0 }}</h6>
                                    <span class="text-muted">Pasien</span>
                                    <a href="/admin-area/pasien" class="btn btn-sm btn-outline-primary">Open</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Website Traffic -->
        <div class="col-12 col-lg-6 order-2 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Website Traffic (Per Month)</h5>
                    </div>
                </div>
                <div class="card-body px-0">
                    <div class="tab-content p-0">
                        <div class="tab-pane fade show active">
                            <div class="d-flex p-4 pt-3">
                                <div>
                                    <small class="text-muted d-block">Today Traffic</small>
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0 me-1">{{ isset($countervisit[0]) ? count($countervisit[0]) : 0 }} Visits</h6>
                                    </div>
                                </div>
                            </div>
                            <div id="profileReportChart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- / Content -->
@endsection
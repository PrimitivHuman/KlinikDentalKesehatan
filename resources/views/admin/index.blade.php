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

        <!-- R4: Quick Stats Row -->
        <div class="col-6 col-md-3 mb-4">
            <div class="card text-center h-100">
                <div class="card-body py-4">
                    <span class="bx bxs-user-account fs-2 text-primary mb-2 d-block"></span>
                    <h4 class="mb-0">{{ $pasien_count ?? 0 }}</h4>
                    <small class="text-muted">Total Pasien</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-4">
            <div class="card text-center h-100">
                <div class="card-body py-4">
                    <span class="bx bx-group fs-2 text-success mb-2 d-block"></span>
                    <h4 class="mb-0">{{ $dokter_count ?? 0 }}</h4>
                    <small class="text-muted">Dokter</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-4">
            <div class="card text-center h-100">
                <div class="card-body py-4">
                    <span class="bx bx-news fs-2 text-info mb-2 d-block"></span>
                    <h4 class="mb-0">{{ $berita_count ?? 0 }}</h4>
                    <small class="text-muted">Artikel</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-4">
            <div class="card text-center h-100">
                <div class="card-body py-4">
                    <span class="bx bx-plus-medical fs-2 text-warning mb-2 d-block"></span>
                    <h4 class="mb-0">{{ $layanan_count ?? 0 }}</h4>
                    <small class="text-muted">Layanan</small>
                </div>
            </div>
        </div>

        <!-- R4: Grafik Pasien Bulanan -->
        <div class="col-12 col-lg-8 mb-4 order-1">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">📈 Tren Pasien Bulanan — {{ date('Y') }}</h5>
                </div>
                <div class="card-body">
                    <div id="chartPasienBulanan"></div>
                </div>
            </div>
        </div>

        <!-- Status Pasien & Status Data (combined) -->
        <div class="col-12 col-lg-4 mb-4 order-2">
            <!-- R4: Distribusi Status Pasien -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title m-0">Status Pasien</h6>
                </div>
                <div class="card-body pt-2">
                    @php
                        $statuses = ['pending' => ['label'=>'Menunggu','color'=>'warning'], 'confirmed'=>['label'=>'Dikonfirmasi','color'=>'success'], 'done'=>['label'=>'Selesai','color'=>'info'], 'cancelled'=>['label'=>'Dibatalkan','color'=>'danger']];
                    @endphp
                    @foreach($statuses as $key => $s)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-label-{{ $s['color'] }}">{{ $s['label'] }}</span>
                        <strong>{{ ($status_stats[$key]->total ?? 0) }}</strong>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Status Data -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title m-0">Status Data Klinik</h6>
                </div>
                <div class="card-body">
                    <ul class="p-0 m-0" style="list-style: none;">
                        <li class="d-flex mb-3 align-items-center justify-content-between">
                            <small><i class="bx bx-task text-primary me-1"></i> Agenda Kegiatan</small>
                            <a href="/admin-area/kegiatan" class="btn btn-xs btn-outline-primary btn-sm py-0">{{ $kegiatan_count ?? 0 }} data</a>
                        </li>
                        <li class="d-flex mb-3 align-items-center justify-content-between">
                            <small><i class="bx bx-images text-warning me-1"></i> Galeri Foto</small>
                            <a href="/admin-area/galeri" class="btn btn-xs btn-outline-warning btn-sm py-0">{{ $galeri_count ?? 0 }} foto</a>
                        </li>
                        <li class="d-flex mb-3 align-items-center justify-content-between">
                            <small><i class="bx bxs-user-account text-danger me-1"></i> Pengguna</small>
                            <a href="/admin-area/akun" class="btn btn-xs btn-outline-danger btn-sm py-0">{{ $user_count ?? 0 }} user</a>
                        </li>
                        <li class="d-flex align-items-center justify-content-between">
                            <small><i class="bx bx-info-circle text-info me-1"></i> Informasi Umum</small>
                            <a href="/admin-area/informasi-umum" class="btn btn-xs btn-outline-info btn-sm py-0">@if (($information_count ?? 0) > 0) Filled @else Empty @endif</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Website Traffic -->
        <div class="col-12 col-lg-12 order-3 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <div class="card-title mb-0">
                        <h5 class="m-0 me-2">🌐 Website Traffic — Minggu Ini</h5>
                        <small class="text-muted">Hari ini: {{ isset($countervisit[0]) ? count($countervisit[0]) : 0 }} kunjungan</small>
                    </div>
                </div>
                <div class="card-body px-0">
                    <div id="profileReportChart"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- / Content -->

@push('scripts')
<script>
// R4: Chart Tren Pasien Bulanan
var chartPasienOptions = {
    series: [{
        name: 'Pasien',
        data: {!! json_encode($chart_pasien_bulanan ?? array_fill(0, 12, 0)) !!}
    }],
    chart: {
        type: 'area',
        height: 230,
        toolbar: { show: false },
        sparkline: { enabled: false }
    },
    dataLabels: { enabled: false },
    stroke: { curve: 'smooth', width: 2 },
    fill: {
        type: 'gradient',
        gradient: { shadeIntensity: 1, opacityFrom: 0.5, opacityTo: 0.1 }
    },
    colors: ['#696cff'],
    xaxis: {
        categories: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des']
    },
    yaxis: { labels: { formatter: val => Math.floor(val) } },
    tooltip: { y: { formatter: val => val + ' pasien' } },
    grid: { borderColor: '#f1f1f1' }
};
var chartPasien = new ApexCharts(document.querySelector("#chartPasienBulanan"), chartPasienOptions);
chartPasien.render();
</script>
@endpush

@endsection
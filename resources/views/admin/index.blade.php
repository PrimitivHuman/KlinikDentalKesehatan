@extends('admin.layout.main')

@section('content')
<!-- Content (UI/UX Pro Max: Modern Minimalist Dashboard) -->

<div class="container-xxl flex-grow-1 container-p-y">
    
    <!-- Welcome Header Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0" style="background: linear-gradient(135deg, rgba(13, 148, 136, 0.08) 0%, rgba(2, 132, 199, 0.05) 100%); border-left: 4px solid var(--admin-primary) !important;">
                <div class="card-body py-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                    <div>
                        <h4 class="fw-bold mb-1 text-dark" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                            Selamat Datang, {{ Auth::user()->name ?? 'Administrator' }} 👋
                        </h4>
                        <p class="text-muted mb-0 small">
                            Berikut adalah ringkasan performa dan aktivitas operasional Klinik FAM Dental Care hari ini.
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a href="/admin-area/pasien" class="btn btn-primary d-inline-flex align-items-center gap-2">
                            <i class="bx bx-user-plus"></i> Kelola Pasien
                        </a>
                        <a href="/" target="_blank" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
                            <i class="bx bx-external-link"></i> Web Publik
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4 Key Stat Metrics -->
    <div class="row g-3 mb-4">
        <!-- Pasien -->
        <div class="col-6 col-lg-3">
            <div class="stat-card-pro">
                <div class="stat-card-icon teal">
                    <i class="bx bxs-user-detail"></i>
                </div>
                <div>
                    <div class="stat-card-val">{{ $pasien_count ?? 0 }}</div>
                    <div class="stat-card-lbl">Total Pasien Terdaftar</div>
                </div>
            </div>
        </div>

        <!-- Dokter -->
        <div class="col-6 col-lg-3">
            <div class="stat-card-pro">
                <div class="stat-card-icon emerald">
                    <i class="bx bx-user-pin"></i>
                </div>
                <div>
                    <div class="stat-card-val">{{ $dokter_count ?? 0 }}</div>
                    <div class="stat-card-lbl">Dokter Spesialis</div>
                </div>
            </div>
        </div>

        <!-- Layanan -->
        <div class="col-6 col-lg-3">
            <div class="stat-card-pro">
                <div class="stat-card-icon amber">
                    <i class="bx bx-plus-medical"></i>
                </div>
                <div>
                    <div class="stat-card-val">{{ $layanan_count ?? 0 }}</div>
                    <div class="stat-card-lbl">Layanan Klinik Aktif</div>
                </div>
            </div>
        </div>

        <!-- Artikel -->
        <div class="col-6 col-lg-3">
            <div class="stat-card-pro">
                <div class="stat-card-icon blue">
                    <i class="bx bx-news"></i>
                </div>
                <div>
                    <div class="stat-card-val">{{ $berita_count ?? 0 }}</div>
                    <div class="stat-card-lbl">Artikel Edukasi</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Middle Row: Chart & Status Breakdown -->
    <div class="row g-4 mb-4">
        
        <!-- Tren Pasien Chart -->
        <div class="col-12 col-lg-8">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0">Tren Kunjungan Pasien — Tahun {{ date('Y') }}</h5>
                        <small class="text-muted">Statistik jumlah reservasi pasien per bulan</small>
                    </div>
                    <span class="badge bg-label-primary">
                        <i class="bx bx-calendar me-1"></i> {{ date('Y') }}
                    </span>
                </div>
                <div class="card-body">
                    <div id="chartPasienBulanan"></div>
                </div>
            </div>
        </div>

        <!-- Status Pasien & Master Summary -->
        <div class="col-12 col-lg-4 d-flex flex-column gap-4">
            
            <!-- Distribusi Status Pasien -->
            <div class="card flex-grow-1">
                <div class="card-header pb-3">
                    <h5 class="card-title mb-0">Status Reservasi Pasien</h5>
                    <small class="text-muted">Ringkasan status antrean saat ini</small>
                </div>
                <div class="card-body pt-2">
                    @php
                        $statuses = [
                            'pending'   => ['label'=>'Menunggu Konfirmasi', 'color'=>'warning', 'icon'=>'bx-time-five'],
                            'confirmed' => ['label'=>'Dikonfirmasi',         'color'=>'success', 'icon'=>'bx-check-double'],
                            'completed' => ['label'=>'Selesai Perawatan',    'color'=>'info',    'icon'=>'bx-badge-check'],
                            'cancelled' => ['label'=>'Dibatalkan',           'color'=>'danger',  'icon'=>'bx-x-circle']
                        ];
                    @endphp

                    <div class="d-flex flex-column gap-3">
                        @foreach($statuses as $key => $s)
                        <div class="d-flex align-items-center justify-content-between p-2 rounded-2" style="background-color: var(--admin-bg);">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-label-{{ $s['color'] }} p-2">
                                    <i class="bx {{ $s['icon'] }}"></i>
                                </span>
                                <span class="small fw-semibold text-dark">{{ $s['label'] }}</span>
                            </div>
                            <span class="fw-bold fs-6 text-dark">{{ ($status_stats[$key]->total ?? 0) }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Master Data Summary Card -->
            <div class="card">
                <div class="card-header pb-2">
                    <h6 class="card-title mb-0 fs-6">Kelola Konten & Data</h6>
                </div>
                <div class="card-body pt-2">
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="/admin-area/kegiatan" class="p-2 border rounded-3 d-flex flex-column text-decoration-none text-dark h-100" style="background-color: var(--admin-bg);">
                                <small class="text-muted" style="font-size: 11px;">Kegiatan</small>
                                <strong class="fs-6 text-primary">{{ $kegiatan_count ?? 0 }} Agenda</strong>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="/admin-area/galeri" class="p-2 border rounded-3 d-flex flex-column text-decoration-none text-dark h-100" style="background-color: var(--admin-bg);">
                                <small class="text-muted" style="font-size: 11px;">Galeri</small>
                                <strong class="fs-6 text-warning">{{ $galeri_count ?? 0 }} Foto</strong>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="/admin-area/informasi-umum" class="p-2 border rounded-3 d-flex flex-column text-decoration-none text-dark h-100" style="background-color: var(--admin-bg);">
                                <small class="text-muted" style="font-size: 11px;">Tentang</small>
                                <strong class="fs-6 text-info">Profil Klinik</strong>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="/admin-area/trash" class="p-2 border rounded-3 d-flex flex-column text-decoration-none text-dark h-100" style="background-color: var(--admin-bg);">
                                <small class="text-muted" style="font-size: 11px;">Recycle Bin</small>
                                <strong class="fs-6 text-danger">Data Terhapus</strong>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
<!-- / Content -->

@push('scripts')
<script>
// UI/UX Pro Max: Chart Tren Pasien dengan Palet Teal Medis
var chartPasienOptions = {
    series: [{
        name: 'Pasien Terdaftar',
        data: {!! json_encode($chart_pasien_bulanan ?? array_fill(0, 12, 0)) !!}
    }],
    chart: {
        type: 'area',
        height: 280,
        fontFamily: 'Inter, sans-serif',
        toolbar: { show: false },
        zoom: { enabled: false }
    },
    dataLabels: { enabled: false },
    stroke: { curve: 'smooth', width: 3, colors: ['#0D9488'] },
    fill: {
        type: 'gradient',
        gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.45,
            opacityTo: 0.05,
            stops: [0, 95, 100],
            colorStops: [
                { offset: 0, color: '#0D9488', opacity: 0.45 },
                { offset: 100, color: '#0D9488', opacity: 0.02 }
            ]
        }
    },
    colors: ['#0D9488'],
    xaxis: {
        categories: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
        axisBorder: { show: false },
        axisTicks: { show: false },
        labels: { style: { colors: '#64748B', fontSize: '12px' } }
    },
    yaxis: {
        labels: {
            formatter: val => Math.floor(val),
            style: { colors: '#64748B', fontSize: '12px' }
        }
    },
    tooltip: {
        theme: 'light',
        y: { formatter: val => val + ' Pasien' },
        marker: { show: true }
    },
    grid: {
        borderColor: '#E2E8F0',
        strokeDashArray: 4,
        padding: { top: 0, right: 10, bottom: 0, left: 10 }
    }
};

var chartPasien = new ApexCharts(document.querySelector("#chartPasienBulanan"), chartPasienOptions);
chartPasien.render();
</script>
@endpush

@endsection
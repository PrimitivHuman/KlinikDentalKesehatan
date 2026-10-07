@extends('main.layout.main')

@section('content')
<main id="main">
  <!-- Breadcrumb Section -->
  <section class="breadcrumbs py-4" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; margin-top: 80px;">
    <div class="container">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h2 class="h4 fw-bold text-dark m-0">Agenda &amp; Kegiatan Klinik</h2>
        <ol class="breadcrumb m-0">
          <li class="breadcrumb-item"><a href="/">Beranda</a></li>
          <li class="breadcrumb-item active" aria-current="page">Agenda Kegiatan</li>
        </ol>
      </div>
    </div>
  </section>

  <!-- Activity List Section -->
  <section class="py-5" style="background: #ffffff;">
    <div class="container" data-aos="fade-up">
      <div class="section-title text-center mb-5">
        <h2 class="fw-bold">Jadwal &amp; Dokumentasi Kegiatan</h2>
        <p class="text-muted">Aktivitas sosial, penyuluhan kesehatan gigi, bakti sosial, dan agenda klinik FAM Dental Care.</p>
      </div>

      <div class="row g-4">
        @forelse ($kegiatans as $item)
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
          <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
            @if ($item->images)
              <img src="{{ asset('/img/activity/' . $item->images) }}" class="card-img-top" alt="{{ $item->judul_kegiatan }}" style="height: 220px; object-fit: cover;">
            @else
              <div class="bg-light d-flex align-items-center justify-content-center text-muted" style="height: 220px;">
                <i class="bi bi-calendar-event" style="font-size: 3rem;"></i>
              </div>
            @endif
            <div class="card-body d-flex flex-column p-4">
              <div class="d-flex align-items-center gap-2 text-muted small mb-2">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1">
                  <i class="bi bi-calendar-check me-1"></i>
                  {{ $item->tgl_kegiatan ? \Carbon\Carbon::parse($item->tgl_kegiatan)->translatedFormat('d F Y') : '-' }}
                </span>
              </div>
              <h5 class="card-title fw-bold text-dark mb-3 fs-6">
                <a href="/agenda/{{ $item->id_kegiatan }}" class="text-decoration-none text-dark hover-primary">
                  {{ $item->judul_kegiatan }}
                </a>
              </h5>
              <div class="card-text text-secondary small flex-grow-1">
                {{ Str::limit(strip_tags($item->deskripsi_kegiatan), 120) }}
              </div>
              <div class="mt-3 pt-3 border-top">
                <a href="/agenda/{{ $item->id_kegiatan }}" class="btn btn-sm btn-outline-primary fw-semibold rounded-pill px-3">
                  Detail Kegiatan <i class="bi bi-arrow-right"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
          <div class="p-5 bg-light rounded-4">
            <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
            <h5 class="mt-3 text-dark fw-bold">Belum Ada Agenda Kegiatan</h5>
            <p class="text-muted">Agenda kegiatan terbaru klinik akan segera kami publikasikan.</p>
            <a href="/" class="btn btn-primary rounded-pill px-4 mt-2">Kembali ke Beranda</a>
          </div>
        </div>
        @endforelse
      </div>

      <!-- Pagination -->
      <div class="d-flex justify-content-center mt-5">
        {{ $kegiatans->links() }}
      </div>
    </div>
  </section>
</main>
@endsection

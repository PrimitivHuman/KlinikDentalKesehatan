@extends('main.layout.main')

@section('content')
<main id="main">
  <!-- Breadcrumb Section -->
  <section class="breadcrumbs py-4" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; margin-top: 80px;">
    <div class="container">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h2 class="h5 fw-bold text-dark m-0">{{ Str::limit($kegiatan->judul_kegiatan, 45) }}</h2>
        <ol class="breadcrumb m-0 small">
          <li class="breadcrumb-item"><a href="/">Beranda</a></li>
          <li class="breadcrumb-item"><a href="/agenda">Agenda</a></li>
          <li class="breadcrumb-item active" aria-current="page">Detail</li>
        </ol>
      </div>
    </div>
  </section>

  <!-- Activity Detail Section -->
  <section class="py-5" style="background: #ffffff;">
    <div class="container">
      <div class="row g-5">
        <!-- Main Content -->
        <div class="col-lg-8" data-aos="fade-up">
          <article class="activity-details">
            <div class="mb-3">
              <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2 fs-6">
                <i class="bi bi-calendar-event me-1"></i>
                {{ $kegiatan->tgl_kegiatan ? \Carbon\Carbon::parse($kegiatan->tgl_kegiatan)->translatedFormat('l, d F Y') : '-' }}
              </span>
            </div>

            <h1 class="h2 fw-bold text-dark mb-4">{{ $kegiatan->judul_kegiatan }}</h1>

            @if ($kegiatan->images)
            <div class="mb-4 rounded-4 overflow-hidden shadow-sm">
              <img src="{{ asset('/img/activity/' . $kegiatan->images) }}" class="img-fluid w-100" alt="{{ $kegiatan->judul_kegiatan }}" style="max-height: 480px; object-fit: cover;">
            </div>
            @endif

            <div class="content activity-body leading-relaxed text-secondary" style="font-size: 16px; line-height: 1.8;">
              {!! \App\Support\Sanitizer::html($kegiatan->deskripsi_kegiatan) !!}
            </div>

            <div class="mt-5 pt-4 border-top d-flex justify-content-between align-items-center flex-wrap gap-3">
              <a href="/agenda" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="bi bi-arrow-left"></i> Lihat Semua Agenda
              </a>
              <a href="/appointment" class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-calendar-check"></i> Jadwalkan Kunjungan
              </a>
            </div>
          </article>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
          <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" style="background: #f8fafc;">
            <h5 class="fw-bold text-dark mb-3">Agenda Lainnya</h5>
            <div class="d-flex flex-column gap-3">
              @forelse ($terbaru as $item)
              <div class="d-flex gap-3 align-items-center border-bottom pb-3">
                @if ($item->images)
                  <img src="{{ asset('/img/activity/' . $item->images) }}" alt="{{ $item->judul_kegiatan }}" class="rounded-3" style="width: 70px; height: 70px; object-fit: cover; flex-shrink: 0;">
                @else
                  <div class="bg-white rounded-3 d-flex align-items-center justify-content-center text-muted" style="width: 70px; height: 70px; flex-shrink: 0;">
                    <i class="bi bi-calendar-check fs-4"></i>
                  </div>
                @endif
                <div>
                  <div class="text-muted small" style="font-size: 12px;">
                    <i class="bi bi-calendar3"></i> {{ $item->tgl_kegiatan ? \Carbon\Carbon::parse($item->tgl_kegiatan)->translatedFormat('d M Y') : '' }}
                  </div>
                  <h6 class="mb-0 fw-semibold">
                    <a href="/agenda/{{ $item->id_kegiatan }}" class="text-dark text-decoration-none hover-primary" style="font-size: 14px;">
                      {{ Str::limit($item->judul_kegiatan, 50) }}
                    </a>
                  </h6>
                </div>
              </div>
              @empty
              <p class="text-muted small mb-0">Belum ada agenda lain.</p>
              @endforelse
            </div>
          </div>

          <!-- CTA Box -->
          <div class="card border-0 shadow-sm rounded-4 p-4 text-center text-white" style="background: linear-gradient(135deg, var(--uipro-primary) 0%, var(--uipro-primary-hover) 100%);">
            <i class="bi bi-heart-pulse fs-1 mb-2"></i>
            <h5 class="fw-bold mb-2">Konsultasi Kesehatan Gigi</h5>
            <p class="small text-white-50 mb-3">Punya pertanyaan seputar agenda atau perawatan gigi? Hubungi kami langsung via WhatsApp.</p>
            <a href="https://wa.me/6285266379191?text=Halo%20Klinik%20FAM%20Dental%20Care" target="_blank" class="btn btn-light rounded-pill px-4 text-primary fw-bold">
              <i class="bi bi-whatsapp"></i> Chat WhatsApp
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
@endsection

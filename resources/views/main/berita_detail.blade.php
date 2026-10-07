@extends('main.layout.main')

@section('content')
<main id="main">
  <!-- Breadcrumb Section -->
  <section class="breadcrumbs py-4" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; margin-top: 80px;">
    <div class="container">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h2 class="h5 fw-bold text-dark m-0">{{ Str::limit($berita->judul, 45) }}</h2>
        <ol class="breadcrumb m-0 small">
          <li class="breadcrumb-item"><a href="/">Beranda</a></li>
          <li class="breadcrumb-item"><a href="/berita">Artikel</a></li>
          <li class="breadcrumb-item active" aria-current="page">Detail</li>
        </ol>
      </div>
    </div>
  </section>

  <!-- Article Detail Section -->
  <section class="py-5" style="background: #ffffff;">
    <div class="container">
      <div class="row g-5">
        <!-- Main Content -->
        <div class="col-lg-8" data-aos="fade-up">
          <article class="blog-details">
            <h1 class="h2 fw-bold text-dark mb-3">{{ $berita->judul }}</h1>

            <div class="d-flex align-items-center gap-3 text-muted small pb-3 mb-4 border-bottom">
              <span class="d-flex align-items-center gap-1">
                <i class="bi bi-person text-primary"></i> {{ $berita->penulis ?? 'Tim Dokter' }}
              </span>
              <span>•</span>
              <span class="d-flex align-items-center gap-1">
                <i class="bi bi-calendar3 text-primary"></i>
                {{ $berita->tgl_terbit ? \Carbon\Carbon::parse($berita->tgl_terbit)->translatedFormat('l, d F Y') : '-' }}
              </span>
            </div>

            @if ($berita->images)
            <div class="mb-4 rounded-3 overflow-hidden shadow-sm">
              <img src="{{ asset('/img/berita/' . $berita->images) }}" class="img-fluid w-100" alt="{{ $berita->judul }}" style="max-height: 440px; object-fit: cover;">
            </div>
            @endif

            <div class="content article-body leading-relaxed text-secondary" style="font-size: 16px; line-height: 1.8;">
              {!! \App\Support\Sanitizer::html($berita->isi) !!}
            </div>

            <div class="mt-5 pt-4 border-top d-flex justify-content-between align-items-center flex-wrap gap-3">
              <a href="/berita" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="bi bi-arrow-left"></i> Kembali ke Artikel
              </a>
              <a href="/appointment" class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-calendar-check"></i> Buat Janji Temu Gigi
              </a>
            </div>
          </article>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
          <div class="sticky-top" style="top: 100px;">
            <!-- CTA Card -->
            <div class="card border-0 rounded-3 shadow-sm p-4 mb-4 text-white" style="background: linear-gradient(135deg, #0d6efd 0%, #0099ff 100%);">
              <h5 class="fw-bold mb-2">Konsultasi Kesehatan Gigi</h5>
              <p class="small text-white-50 mb-3">Jadwalkan pemeriksaan rutin atau konsultasi masalah gigi Anda dengan dokter kami.</p>
              <a href="/appointment" class="btn btn-light text-primary fw-bold rounded-pill w-100 py-2">
                Daftar Online Sekarang
              </a>
            </div>

            <!-- Recent Articles -->
            @if ($terbaru->isNotEmpty())
            <div class="card border-0 rounded-3 shadow-sm p-4">
              <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">Artikel Terkait Lainnya</h5>
              <div class="d-flex flex-column gap-3">
                @foreach ($terbaru as $tb)
                <div class="d-flex gap-3 align-items-center">
                  @if ($tb->images)
                    <img src="{{ asset('/img/berita/' . $tb->images) }}" class="rounded" width="70" height="70" style="object-fit: cover;" alt="{{ $tb->judul }}">
                  @else
                    <div class="rounded bg-light d-flex align-items-center justify-content-center text-muted" style="width: 70px; height: 70px;">
                      <i class="bi bi-newspaper"></i>
                    </div>
                  @endif
                  <div class="flex-grow-1">
                    <h6 class="mb-1" style="font-size: 14px;">
                      <a href="/berita/{{ $tb->slug }}" class="text-decoration-none text-dark fw-semibold">
                        {{ Str::limit($tb->judul, 50) }}
                      </a>
                    </h6>
                    <span class="text-muted" style="font-size: 11px;">
                      {{ $tb->tgl_terbit ? \Carbon\Carbon::parse($tb->tgl_terbit)->translatedFormat('d M Y') : '' }}
                    </span>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
@endsection

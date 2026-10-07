@extends('main.layout.main')

@section('content')
<main id="main">
  <!-- Breadcrumb Section -->
  <section class="breadcrumbs py-4" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; margin-top: 80px;">
    <div class="container">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h2 class="h4 fw-bold text-dark m-0">Artikel &amp; Tips Kesehatan Gigi</h2>
        <ol class="breadcrumb m-0">
          <li class="breadcrumb-item"><a href="/">Beranda</a></li>
          <li class="breadcrumb-item active" aria-current="page">Artikel</li>
        </ol>
      </div>
    </div>
  </section>

  <!-- News List Section -->
  <section class="py-5" style="background: #ffffff;">
    <div class="container" data-aos="fade-up">
      <div class="section-title text-center mb-5">
        <h2 class="fw-bold">Edukasi &amp; Berita Terbaru</h2>
        <p class="text-muted">Panduan merawat gigi, tips kesehatan mulut keluarga, dan kabar terkini dari Klinik FAM Dental Care.</p>
      </div>

      <div class="row g-4">
        @forelse ($beritas as $item)
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
          <div class="card h-100 shadow-sm border-0 rounded-3 overflow-hidden">
            @if ($item->images)
              <img src="{{ asset('/img/berita/' . $item->images) }}" class="card-img-top" alt="{{ $item->judul }}" style="height: 220px; object-fit: cover;">
            @else
              <div class="bg-light d-flex align-items-center justify-content-center text-muted" style="height: 220px;">
                <i class="bi bi-newspaper" style="font-size: 3rem;"></i>
              </div>
            @endif
            <div class="card-body d-flex flex-column p-4">
              <div class="d-flex align-items-center gap-2 text-muted small mb-2">
                <i class="bi bi-calendar3"></i>
                <span>{{ $item->tgl_terbit ? \Carbon\Carbon::parse($item->tgl_terbit)->translatedFormat('d F Y') : '-' }}</span>
                <span>•</span>
                <i class="bi bi-person"></i>
                <span>{{ $item->penulis ?? 'Tim Dokter' }}</span>
              </div>
              <h5 class="card-title fw-bold text-dark mb-3">
                <a href="/berita/{{ $item->slug }}" class="text-decoration-none text-dark hover-primary">
                  {{ $item->judul }}
                </a>
              </h5>
              <p class="card-text text-secondary small flex-grow-1">
                {{ Str::limit(strip_tags($item->isi), 120) }}
              </p>
              <div class="mt-3 pt-3 border-top">
                <a href="/berita/{{ $item->slug }}" class="btn btn-sm btn-outline-primary fw-semibold rounded-pill px-3">
                  Baca Selengkapnya <i class="bi bi-arrow-right"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
          <div class="p-5 bg-light rounded-3">
            <i class="bi bi-journal-x text-muted" style="font-size: 3rem;"></i>
            <h5 class="mt-3 text-dark fw-bold">Belum Ada Artikel Dipublikasikan</h5>
            <p class="text-muted">Artikel edukasi dan tips kesehatan gigi sedang kami siapkan untuk Anda.</p>
            <a href="/" class="btn btn-primary rounded-pill px-4 mt-2">Kembali ke Beranda</a>
          </div>
        </div>
        @endforelse
      </div>

      <div class="d-flex justify-content-center mt-5">
        {{ $beritas->links() }}
      </div>
    </div>
  </section>
</main>
@endsection

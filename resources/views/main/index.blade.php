@extends('main.layout.main')

@section('content')

<!-- ======= Hero Section (UI/UX Pro Max: Trust & Conversion) ======= -->
<section id="hero">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-7" data-aos="fade-up" data-aos-delay="100">
        
        <div class="hero-badge">
          <i class="bi bi-shield-check"></i>
          <span>Klinik Gigi Modern & Terpercaya Sejak 2019</span>
        </div>

        <h1 class="hero-title">
          Senyum Sehat & Percaya Diri Bersama <span>Family Dental Care</span>
        </h1>

        <p class="hero-subtitle">
          Rasakan kenyamanan perawatan gigi berstandar tinggi dengan tim dokter spesialis berlisensi, teknologi sterilisasi mutakhir, dan pendekatan ramah yang bebas cemas untuk seluruh anggota keluarga.
        </p>

        <div class="hero-cta-group">
          <a href="/appointment" class="btn-hero-primary">
            <i class="bi bi-calendar2-check-fill"></i>
            <span>Buat Janji Temu Online</span>
          </a>
          <a href="#services" class="btn-hero-secondary scrollto">
            <span>Jelajahi Layanan</span>
            <i class="bi bi-arrow-right"></i>
          </a>
        </div>

        <!-- Social Proof Key Stats -->
        <div class="hero-stats-bar">
          <div class="row g-3">
            <div class="col-6 col-md-3">
              <div class="stat-item">
                <div class="stat-icon"><i class="bi bi-geo-alt"></i></div>
                <div>
                  <div class="stat-num">5</div>
                  <div class="stat-label">Cabang Bandung</div>
                </div>
              </div>
            </div>
            <div class="col-6 col-md-3">
              <div class="stat-item">
                <div class="stat-icon"><i class="bi bi-person-badge"></i></div>
                <div>
                  <div class="stat-num">8+</div>
                  <div class="stat-label">Dokter Spesialis</div>
                </div>
              </div>
            </div>
            <div class="col-6 col-md-3">
              <div class="stat-item">
                <div class="stat-icon"><i class="bi bi-stars"></i></div>
                <div>
                  <div class="stat-num">99%</div>
                  <div class="stat-label">Pasien Puas</div>
                </div>
              </div>
            </div>
            <div class="col-6 col-md-3">
              <div class="stat-item">
                <div class="stat-icon"><i class="bi bi-shield-lock"></i></div>
                <div>
                  <div class="stat-num">100%</div>
                  <div class="stat-label">Steril & Higienis</div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

      <div class="col-lg-5 text-center mt-5 mt-lg-0" data-aos="fade-left" data-aos-delay="200">
        <div class="position-relative d-inline-block">
          <img src="{{ asset('assets/img/features.jpg') }}" alt="Perawatan Gigi Modern" class="img-fluid rounded-4 shadow-lg" style="max-height: 480px; object-fit: cover; border: 4px solid #FFFFFF;">
          
          <div class="position-absolute bottom-0 start-0 translate-middle-y bg-white p-3 rounded-3 shadow-md d-none d-sm-flex align-items-center gap-3 ms-n3 border" style="border-color: var(--uipro-border) !important; max-width: 260px; text-align: left;">
            <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; flex-shrink: 0;">
              <i class="bi bi-check-lg fs-5"></i>
            </div>
            <div>
              <div class="fw-bold text-dark small">Painless Dentistry</div>
              <div class="text-muted" style="font-size: 11px;">Perawatan lembut tanpa rasa sakit</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section><!-- End Hero -->

<!-- ======= Nilai Unggulan (Why Choose Us) ======= -->
<section class="pt-3 pb-5" style="background: #FFFFFF; border-bottom: 1px solid var(--uipro-border);">
  <div class="container" data-aos="fade-up">
    <div class="row g-4">
      <div class="col-md-4">
        <div class="d-flex align-items-start gap-3 p-3 rounded-3 h-100" style="background: var(--uipro-bg);">
          <div class="stat-icon" style="background: var(--uipro-primary-light); color: var(--uipro-primary); width: 48px; height: 48px; flex-shrink: 0;">
            <i class="bi bi-award-fill"></i>
          </div>
          <div>
            <h5 class="fw-bold mb-1 fs-6 text-dark">Dokter Berlisensi Resmi</h5>
            <p class="text-muted small mb-0">Semua dokter memiliki STR & SIP aktif dengan keahlian spesialisasi kedokteran gigi modern.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="d-flex align-items-start gap-3 p-3 rounded-3 h-100" style="background: var(--uipro-bg);">
          <div class="stat-icon" style="background: var(--uipro-primary-light); color: var(--uipro-primary); width: 48px; height: 48px; flex-shrink: 0;">
            <i class="bi bi-cpu-fill"></i>
          </div>
          <div>
            <h5 class="fw-bold mb-1 fs-6 text-dark">Peralatan Digital Mutakhir</h5>
            <p class="text-muted small mb-0">Didukung alat diagnostik presisi dan teknik pembersihan ultrasonik berstandar internasional.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="d-flex align-items-start gap-3 p-3 rounded-3 h-100" style="background: var(--uipro-bg);">
          <div class="stat-icon" style="background: var(--uipro-primary-light); color: var(--uipro-primary); width: 48px; height: 48px; flex-shrink: 0;">
            <i class="bi bi-emoji-smile-fill"></i>
          </div>
          <div>
            <h5 class="fw-bold mb-1 fs-6 text-dark">Ramah Seluruh Keluarga</h5>
            <p class="text-muted small mb-0">Ruang tunggu yang tenang, konsultasi transparan, dan penanganan yang sangat ramah bagi anak.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ======= Services Section ======= -->
<section id="services" class="services py-5">
  <div class="container" data-aos="fade-up">

    <div class="section-title">
      <h2>Layanan Perawatan Gigi Kami</h2>
      <p>Pilihan perawatan komprehensif mulai dari pembersihan karang gigi preventif, estetika senyum, hingga tindakan restorasi.</p>
    </div>

    <div class="row g-4">
      @if(isset($layanans) && $layanans->count() > 0)
        @foreach($layanans as $index => $layanan)
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($index % 3 + 1) * 100 }}">
          <div class="icon-box">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <div class="icon m-0">
                <i class="bx {{ $layanan->ikon ?? 'bx-plus-medical' }}"></i>
              </div>
              @if($layanan->durasi)
              <span class="badge bg-light text-muted border">
                <i class="bi bi-clock me-1"></i>{{ $layanan->durasi }}
              </span>
              @endif
            </div>

            <h4 class="title">
              <a href="/appointment">{{ $layanan->nama_layanan }}</a>
            </h4>
            
            <p class="description">
              {{ Str::limit(strip_tags($layanan->deskripsi), 130) }}
            </p>

            <div class="d-flex align-items-center justify-content-between mt-auto pt-3 border-top">
              @if($layanan->harga_mulai)
              <span class="price-pill">{{ $layanan->harga_range }}</span>
              @else
              <span class="price-pill">Konsultasi Dokter</span>
              @endif

              <a href="/appointment" class="text-primary fw-semibold small d-inline-flex align-items-center gap-1 text-decoration-none">
                Reservasi <i class="bi bi-chevron-right"></i>
              </a>
            </div>
          </div>
        </div>
        @endforeach
      @else
        <div class="col-12 text-center py-4">
          <p class="text-muted">Data layanan sedang diperbarui.</p>
        </div>
      @endif
    </div>

  </div>
</section><!-- End Services Section -->

<!-- ======= Doctors Section ======= -->
<section id="doctors" class="doctors py-5" style="background: #FFFFFF;">
  <div class="container" data-aos="fade-up">

    <div class="section-title">
      <h2>Tim Dokter Spesialis Gigi</h2>
      <p>Dokter gigi kami berdedikasi tinggi, berpendidikan profesional, dan ramah dalam memberikan diagnosis serta solusi terbaik.</p>
    </div>

    <div class="row g-4">
      @foreach ($dokter as $data)
      <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
        <div class="member h-100 d-flex flex-column">
          <div class="member-img">
            @if($data->images && file_exists(public_path('img/dokter/'.$data->images)))
              <img src="{{ asset('img/dokter/'.$data->images) }}" alt="{{ $data->nama_dokter }}">
            @else
              <img src="{{ asset('assets/img/doctors/doctors-1.jpg') }}" alt="{{ $data->nama_dokter }}">
            @endif
          </div>
          <div class="member-info d-flex flex-column flex-grow-1 justify-content-between">
            <div>
              <h4>{{ $data->nama_dokter }}</h4>
              <div class="sip-badge mt-1 mb-2">
                {{ $data->sip_dokter && $data->sip_dokter !== '0' ? 'SIP: '.$data->sip_dokter : 'Dokter Spesialis' }}
              </div>
            </div>
            
            <div class="pt-3 border-top mt-2">
              <small class="text-muted d-block fw-bold mb-1" style="font-size: 11px;">
                <i class="bi bi-calendar3 text-primary me-1"></i> Jadwal Praktik:
              </small>
              <div class="small text-dark mb-3" style="font-size: 12px; line-height: 1.4;">
                {{ $data->jadwal_dokter ?? 'Sesuai Perjanjian' }}
              </div>
              <a href="/appointment" class="btn btn-sm btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-1" style="border-radius: var(--uipro-radius-pill);">
                <i class="bi bi-calendar-plus"></i> Jadwalkan
              </a>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>

  </div>
</section><!-- End Doctors Section -->

<!-- ======= About Us Section ======= -->
<section id="about" class="about py-5">
  <div class="container" data-aos="fade-up">

    <div class="section-title">
      <h2>Tentang Family Dental Care</h2>
      <p>Klinik gigi keluarga terintegrasi di Bandung yang mengedepankan kualitas dan kenyamanan setiap kunjungan Anda.</p>
    </div>

    <div class="row align-items-center g-5">
      <div class="col-lg-6" data-aos="fade-right">
        <div class="position-relative">
          @if($tentang?->foto_sampul && file_exists(public_path('main/img/'.$tentang->foto_sampul)))
            <img src="{{ asset('main/img/'.$tentang->foto_sampul) }}" class="img-fluid rounded-4 shadow-md w-100" alt="Family Dental Care">
          @else
            <img src="{{ asset('assets/img/about.jpg') }}" class="img-fluid rounded-4 shadow-md w-100" alt="Family Dental Care">
          @endif
        </div>
      </div>
      
      <div class="col-lg-6" data-aos="fade-left">
        <div class="content">
          <div class="mb-4 text-secondary leading-relaxed" style="font-size: 15px;">
            {!! \App\Support\Sanitizer::html($tentang?->informasi_umum ?? 'Family Dental Care didirikan untuk memberikan solusi kesehatan gigi terpercaya bagi keluarga dengan suasana yang ramah dan nyaman.') !!}
          </div>

          <div class="p-3 rounded-3 mb-3 bg-white border" style="border-color: var(--uipro-border) !important;">
            <h5 class="fw-bold mb-2 fs-6 text-dark d-flex align-items-center gap-2">
              <i class="bi bi-bullseye text-primary"></i> Visi Kami
            </h5>
            <div class="text-muted small fst-italic">
              {!! \App\Support\Sanitizer::html($tentang?->visi ?? 'Menjadi pusat layanan kesehatan gigi keluarga yang nyaman, ramah, dan profesional.') !!}
            </div>
          </div>

          <div class="p-3 rounded-3 bg-white border" style="border-color: var(--uipro-border) !important;">
            <h5 class="fw-bold mb-2 fs-6 text-dark d-flex align-items-center gap-2">
              <i class="bi bi-check2-circle text-success"></i> Misi Kami
            </h5>
            <div class="text-muted small">
              {!! \App\Support\Sanitizer::html($tentang?->misi ?? 'Memberikan pelayanan kesehatan gigi yang berkualitas dan profesional untuk seluruh keluarga.') !!}
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</section><!-- End About Us Section -->

<!-- ======= Gallery Section ======= -->
<section id="gallery" class="gallery py-5" style="background: #FFFFFF;">
  <div class="container" data-aos="fade-up">

    <div class="section-title">
      <h2>Galeri Tindakan & Fasilitas</h2>
      <p>Hasil perawatan estetika, ortodonti, pemutihan gigi, dan fasilitas klinik yang bersih serta higienis.</p>
    </div>

    <div class="row g-4">
      @foreach ($galeri as $data)
      <div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="100">
        <div class="gallery-item">
          <a href="{{ asset('/img/gallery/'.$data->images) }}" class="gallery-lightbox">
            <img src="{{ asset('/img/gallery/'.$data->images) }}" alt="{{ strip_tags($data->judul) }}">
          </a>
          <h5 class="gallery-title">{{ strip_tags($data->judul) }}</h5>
        </div>
      </div>
      @endforeach
    </div>

  </div>
</section><!-- End Gallery Section -->

@if (isset($beritas) && $beritas->isNotEmpty())
<!-- ======= Berita & Edukasi Section ======= -->
<section id="berita" class="py-5" style="background: #f8fafc;">
  <div class="container" data-aos="fade-up">
    <div class="section-title">
      <h2>Artikel &amp; Tips Gigi Terkini</h2>
      <p>Informasi kesehatan dan panduan perawatan gigi langsung dari tim medis profesional kami.</p>
    </div>

    <div class="row g-4">
      @foreach ($beritas as $item)
      <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
        <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
          @if ($item->images)
            <img src="{{ asset('/img/berita/' . $item->images) }}" class="card-img-top" alt="{{ $item->judul }}" style="height: 200px; object-fit: cover;">
          @else
            <div class="bg-light d-flex align-items-center justify-content-center text-muted" style="height: 200px;">
              <i class="bi bi-newspaper" style="font-size: 2.5rem;"></i>
            </div>
          @endif
          <div class="card-body p-4 d-flex flex-column">
            <div class="text-muted small mb-2 d-flex align-items-center gap-2">
              <i class="bi bi-calendar3"></i>
              <span>{{ $item->tgl_terbit ? \Carbon\Carbon::parse($item->tgl_terbit)->translatedFormat('d M Y') : '' }}</span>
              <span>•</span>
              <i class="bi bi-person"></i>
              <span>{{ $item->penulis ?? 'Tim Dokter' }}</span>
            </div>
            <h5 class="card-title fw-bold fs-6 mb-2">
              <a href="/berita/{{ $item->slug }}" class="text-decoration-none text-dark">
                {{ $item->judul }}
              </a>
            </h5>
            <p class="card-text text-secondary small flex-grow-1">
              {{ Str::limit(strip_tags($item->isi), 100) }}
            </p>
            <div class="mt-3 pt-2">
              <a href="/berita/{{ $item->slug }}" class="text-primary fw-semibold small text-decoration-none">
                Baca Selengkapnya <i class="bi bi-arrow-right"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>

    <div class="text-center mt-4">
      <a href="/berita" class="btn btn-outline-primary rounded-pill px-4">
        Lihat Semua Artikel <i class="bi bi-arrow-right"></i>
      </a>
    </div>
  </div>
</section>
@endif

@if (isset($kegiatans) && $kegiatans->isNotEmpty())
<!-- ======= Agenda Kegiatan Section ======= -->
<section id="agenda" class="py-5" style="background: #ffffff; border-top: 1px solid var(--uipro-border);">
  <div class="container" data-aos="fade-up">
    <div class="section-title">
      <h2>Agenda &amp; Kegiatan Klinik</h2>
      <p>Aktivitas sosial, penyuluhan kesehatan gigi, dan agenda pelayanan terkini FAM Dental Care.</p>
    </div>

    <div class="row g-4">
      @foreach ($kegiatans as $keg)
      <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
        <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
          @if ($keg->images)
            <img src="{{ asset('/img/activity/' . $keg->images) }}" class="card-img-top" alt="{{ $keg->judul_kegiatan }}" style="height: 200px; object-fit: cover;">
          @else
            <div class="bg-light d-flex align-items-center justify-content-center text-muted" style="height: 200px;">
              <i class="bi bi-calendar-event" style="font-size: 2.5rem;"></i>
            </div>
          @endif
          <div class="card-body p-4 d-flex flex-column">
            <div class="mb-2">
              <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 small">
                <i class="bi bi-calendar-check me-1"></i>
                {{ $keg->tgl_kegiatan ? \Carbon\Carbon::parse($keg->tgl_kegiatan)->translatedFormat('d M Y') : '' }}
              </span>
            </div>
            <h5 class="card-title fw-bold fs-6 mb-2">
              <a href="/agenda/{{ $keg->id_kegiatan }}" class="text-decoration-none text-dark">
                {{ $keg->judul_kegiatan }}
              </a>
            </h5>
            <div class="card-text text-secondary small flex-grow-1">
              {{ Str::limit(strip_tags($keg->deskripsi_kegiatan), 110) }}
            </div>
            <div class="mt-3 pt-2">
              <a href="/agenda/{{ $keg->id_kegiatan }}" class="text-primary fw-semibold small text-decoration-none">
                Detail Kegiatan <i class="bi bi-arrow-right"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>

    <div class="text-center mt-4">
      <a href="/agenda" class="btn btn-outline-primary rounded-pill px-4">
        Lihat Semua Agenda <i class="bi bi-arrow-right"></i>
      </a>
    </div>
  </div>
</section>
@endif

<!-- ======= Contact & Location Section ======= -->
<section id="contact" class="contact py-5">
  <div class="container" data-aos="fade-up">

    <div class="section-title">
      <h2>Lokasi & Kontak Resmi</h2>
      <p>Kunjungi klinik kami di Bandung atau hubungi kami untuk berkonsultasi langsung dengan petugas kami.</p>
    </div>

    <div class="mb-4 rounded-4 overflow-hidden shadow-sm" style="border: 1px solid var(--uipro-border);">
      <iframe style="border:0; width: 100%; height: 360px; display: block;" src="https://maps.google.com/maps?q=family%20dental%20care%20leuwi%20panjang%20bandung&t=&z=15&ie=UTF8&iwloc=&output=embed" frameborder="0" allowfullscreen></iframe>
    </div>

    <div class="row g-4 mt-2">
      <div class="col-lg-4 col-md-6">
        <div class="info-box d-flex flex-column align-items-center justify-content-between">
          <div>
            <i class="bx bx-map"></i>
            <h3 class="fs-6 fw-bold">Alamat Utama</h3>
            <p class="small text-muted mb-0">Jl. Leuwi Panjang No.52a, Situsaeur, Kec. Bojongloa Kidul, Kota Bandung, Jawa Barat 40234</p>
          </div>
          <a href="https://maps.google.com/?q=Jl.+Leuwi+Panjang+No.52a,+Bandung" target="_blank" class="btn btn-outline-primary btn-contact">
            <i class="bx bx-navigation"></i> Petunjuk Arah
          </a>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="info-box d-flex flex-column align-items-center justify-content-between">
          <div>
            <i class="bx bx-envelope"></i>
            <h3 class="fs-6 fw-bold">Email Informasi</h3>
            <p class="small text-muted mb-0">fdcbandung52@gmail.com</p>
          </div>
          <a href="mailto:fdcbandung52@gmail.com" class="btn btn-outline-primary btn-contact">
            <i class="bx bx-mail-send"></i> Kirim Pertanyaan
          </a>
        </div>
      </div>

      <div class="col-lg-4 col-md-12">
        <div class="info-box d-flex flex-column align-items-center justify-content-between">
          <div>
            <i class="bx bxl-whatsapp" style="color: var(--uipro-accent) !important; background: var(--uipro-accent-light) !important;"></i>
            <h3 class="fs-6 fw-bold">WhatsApp Resmi</h3>
            <p class="small text-muted mb-0">0852-6637-9191 (Respon cepat jam operasional)</p>
          </div>
          <a href="https://wa.me/6285266379191?text=Halo%20Klinik%20FAM%20Dental%20Care,%20saya%20ingin%20reservasi" target="_blank" class="btn btn-success btn-contact" style="background-color: var(--uipro-accent) !important; border-color: var(--uipro-accent) !important;">
            <i class="bx bxl-whatsapp"></i> Chat WhatsApp
          </a>
        </div>
      </div>
    </div>

  </div>
</section><!-- End Contact Section -->

@endsection
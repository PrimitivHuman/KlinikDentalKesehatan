<!-- ======= Top Bar ======= -->
<div id="topbar" class="d-flex align-items-center fixed-top">
  <div class="container d-flex align-items-center justify-content-between">
    <div class="align-items-center d-none d-md-flex gap-2">
      <i class="bi bi-clock me-1"></i>
      <span>Senin – Sabtu: 08.00 – 20.00 WIB | Minggu: Sesuai Perjanjian</span>
    </div>
    <div class="d-flex align-items-center gap-2">
      <a href="https://wa.me/6285266379191?text=Halo%20Klinik%20FAM%20Dental%20Care,%20saya%20ingin%20konsultasi" target="_blank" class="btn btn-outline-light d-inline-flex align-items-center gap-1">
        <i class="bi bi-whatsapp"></i>
        <span>WhatsApp: 0852-6637-9191</span>
      </a>
      <a href="/login" class="text-white-50 ms-2 small d-none d-lg-inline-block text-decoration-none" title="Masuk Portal Admin">
        <i class="bi bi-shield-lock me-1"></i> Portal Admin
      </a>
    </div>
  </div>
</div>

<!-- ======= Header ======= -->
<header id="header" class="fixed-top">
  <div class="container d-flex align-items-center justify-content-between">

    <a href="/" class="logo d-flex align-items-center text-decoration-none me-auto me-lg-0">
      <img src="{{ asset('assets/img/logo.png') }}" alt="Klinik FAM Dental Care" class="img-fluid me-2">
      <span class="d-none d-sm-inline-block fw-bold fs-5 text-dark" style="font-family: var(--uipro-font-heading);">
        FAM <span style="color: var(--uipro-primary);">Dental Care</span>
      </span>
    </a>

    <nav id="navbar" class="navbar order-last order-lg-0">
      <ul>
        <li><a class="nav-link scrollto active" href="/#hero">Beranda</a></li>
        <li><a class="nav-link scrollto" href="/#services">Layanan</a></li>
        <li><a class="nav-link scrollto" href="/#doctors">Dokter Spesialis</a></li>
        <li><a class="nav-link scrollto" href="/#about">Tentang Kami</a></li>
        <li><a class="nav-link scrollto" href="/#gallery">Galeri</a></li>
        <li><a class="nav-link scrollto" href="/#contact">Lokasi & Kontak</a></li>
      </ul>
      <i class="bi bi-list mobile-nav-toggle"></i>
    </nav><!-- .navbar -->

    <div class="d-flex align-items-center gap-2">
      <a href="/appointment" class="appointment-btn">
        <i class="bi bi-calendar-check"></i>
        <span class="d-none d-md-inline">Buat</span> Janji Temu
      </a>
    </div>

  </div>
</header><!-- End Header -->
<!-- Menu (UI/UX Pro Max: Minimalist & Clean Sidebar) -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo d-flex align-items-center justify-content-between">
        <a href="/admin-area" class="app-brand-link text-decoration-none d-flex align-items-center">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Klinik FAM" style="max-height: 36px; border-radius: 6px;">
            <span class="ms-2 fw-bold fs-6 text-dark" style="font-family: var(--admin-font-heading);">
                FAM <span style="color: var(--admin-primary);">Dental</span>
            </span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-2">
        <!-- Dashboard -->
        <li class="menu-item @if ($menu == 'home') active @endif">
            <a href="/admin-area" class="menu-link">
                <i class="menu-icon tf-icons bx bx-grid-alt"></i>
                <div>Dashboard</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Manajemen Pasien</span>
        </li>

        <li class="menu-item @if ($menu == 'pasien') active @endif">
            <a href="/admin-area/pasien" class="menu-link">
                <i class='menu-icon tf-icons bx bxs-user-detail'></i>
                <div>Data Pasien</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Master Data Klinik</span>
        </li>

        <li class="menu-item @if ($menu == 'dokter') active @endif">
            <a href="/admin-area/dokter" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user-pin"></i>
                <div>Dokter Spesialis</div>
            </a>
        </li>

        <li class="menu-item @if ($menu == 'layanan') active @endif">
            <a href="/admin-area/layanan" class="menu-link">
                <i class="menu-icon tf-icons bx bx-plus-medical"></i>
                <div>Layanan Perawatan</div>
            </a>
        </li>

        <li class="menu-item @if ($menu == 'galeri') active open @endif">
            <a href="/admin-area/galeri" class="menu-link">
                <i class="menu-icon tf-icons bx bx-images"></i>
                <div>Galeri & Hasil</div>
            </a>
        </li>

        <li class="menu-item @if ($menu == 'informasi') active @endif">
            <a href="/admin-area/informasi-umum" class="menu-link">
                <i class="menu-icon tf-icons bx bx-info-circle"></i>
                <div>Profil & Tentang</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Publikasi & Agenda</span>
        </li>

        <li class="menu-item @if ($menu == 'berita') active @endif">
            <a href="/admin-area/berita" class="menu-link">
                <i class="menu-icon tf-icons bx bx-news"></i>
                <div>Artikel Edukasi</div>
            </a>
        </li>

        <li class="menu-item @if ($menu == 'kegiatan') active @endif">
            <a href="/admin-area/kegiatan" class="menu-link">
                <i class="menu-icon tf-icons bx bx-calendar-event"></i>
                <div>Agenda Kegiatan</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Sistem & Pengaturan</span>
        </li>

        @if(Auth::check() && Auth::user()->role === 'superadmin')
        <li class="menu-item @if ($menu == 'pengguna') active @endif">
            <a href="/admin-area/akun" class="menu-link">
                <i class='menu-icon tf-icons bx bx-shield-quarter'></i>
                <div>Manajemen Akun</div>
            </a>
        </li>
        @endif

        <li class="menu-item @if ($menu == 'trash') active @endif">
            <a href="/admin-area/trash" class="menu-link">
                <i class="menu-icon tf-icons bx bx-trash-alt"></i>
                <div>Recycle Bin</div>
            </a>
        </li>

        <li class="menu-item mt-3">
            <a href="/" target="_blank" class="menu-link text-primary" style="background-color: var(--admin-primary-light);">
                <i class="menu-icon tf-icons bx bx-link-external text-primary"></i>
                <div>Lihat Website Publik</div>
            </a>
        </li>
    </ul>
</aside>
<!-- / Menu -->

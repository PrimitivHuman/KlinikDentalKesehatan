<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="d-none d-md-flex align-items-center">
        <span class="badge bg-label-info me-2"><i class="bx bx-clinic me-1"></i> Family Dental Care</span>
        <span class="text-muted small">Bandung Branch</span>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <ul class="navbar-nav flex-row align-items-center ms-auto gap-2">
            <!-- View Website Button -->
            <li class="nav-item d-none d-sm-block">
                <a href="/" target="_blank" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1" style="border-radius: var(--admin-radius-pill);">
                    <i class="bx bx-globe"></i>
                    <span>Web Publik</span>
                </a>
            </li>

            <!-- User Dropdown -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow d-flex align-items-center gap-2" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        @if(Auth::check() && Auth::user()->profile_pict && file_exists(public_path('img/account/'.Auth::user()->profile_pict)))
                            <img src="{{ asset('img/account/'.Auth::user()->profile_pict) }}" alt class="w-px-40 h-auto rounded-circle" />
                        @else
                            <div class="w-px-40 h-px-40 rounded-circle bg-teal text-white d-flex align-items-center justify-content-center fw-bold" style="background-color: var(--admin-primary); height: 40px;">
                                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div class="d-none d-lg-block text-start">
                        <span class="fw-semibold d-block text-dark small" style="line-height: 1.2;">{{ Auth::user()->name ?? 'Administrator' }}</span>
                        <span class="badge bg-label-primary text-uppercase mt-1" style="font-size: 10px; padding: 2px 8px;">
                            {{ Auth::user()->role ?? 'admin' }}
                        </span>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-md border-0" style="border-radius: var(--admin-radius-md);">
                    <li class="px-3 py-2 border-bottom">
                        <div class="fw-bold text-dark">{{ Auth::user()->name ?? 'User' }}</div>
                        <div class="text-muted small">{{ Auth::user()->email ?? '' }}</div>
                    </li>
                    <li>
                        <a class="dropdown-item py-2" href="/admin-area/akun/detail">
                            <i class="bx bx-user me-2 text-primary"></i>
                            <span class="align-middle">Profil Saya</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item py-2" href="/admin-area/pengaturan">
                            <i class="bx bx-cog me-2 text-primary"></i>
                            <span class="align-middle">Pengaturan Akun</span>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider m-0"></div>
                    </li>
                    <li>
                        <a class="dropdown-item py-2 text-danger" href="/logout">
                            <i class="bx bx-power-off me-2"></i>
                            <span class="align-middle">Keluar (Logout)</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>
</nav>

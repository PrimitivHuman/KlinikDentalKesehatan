<!DOCTYPE html>
<html
  lang="id"
  class="light-style customizer-hide"
  dir="ltr"
  data-theme="theme-default"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Masuk Portal Admin | Klinik FAM Dental Care</title>
    <meta name="description" content="Masuk ke portal administrasi Klinik FAM Dental Care Bandung." />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/logo.png') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap"
      rel="stylesheet"
    />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('admin/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('admin/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('admin/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('admin/css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/css/admin-pro-max.css') }}" />

    <style>
      body {
        font-family: 'Inter', sans-serif !important;
        background: radial-gradient(circle at 50% 15%, #CCFBF1 0%, #F0FDFA 30%, #F8FAFC 75%) !important;
        min-height: 100vh;
      }
      .login-pro-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 20px;
        box-shadow: 0 20px 40px -15px rgba(13, 148, 136, 0.15), 0 0 1px 1px rgba(226, 232, 240, 0.8);
        padding: 42px 38px;
        width: 100%;
        max-width: 440px;
      }
      .brand-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 14px;
        background: #F0FDFA;
        color: #0F766E;
        border: 1px solid #CCFBF1;
        border-radius: 9999px;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 12px;
      }
    </style>
  </head>

  <body>
    <div class="d-flex align-items-center justify-content-center min-vh-100 p-3">
      <div class="login-pro-card">
        
        <!-- Logo & Header -->
        <div class="text-center mb-4">
          <div class="brand-badge">
            <i class="bx bx-shield-quarter"></i>
            <span>Portal Otentikasi Resmi</span>
          </div>

          <a href="/" class="d-block mb-3 text-decoration-none">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Klinik FAM Dental Care" style="max-height: 48px; border-radius: 8px;">
          </a>
          
          <h4 class="fw-bold text-dark mb-1" style="font-family: 'Plus Jakarta Sans', sans-serif;">
            Selamat Datang Kembali
          </h4>
          <p class="text-muted small mb-0">
            Masuk dengan kredensial terdaftar untuk mengakses dashboard klinik.
          </p>
        </div>

        @if (Session::has('message'))
          <div class="alert alert-danger d-flex align-items-center gap-2 small py-2 px-3 mb-3 border-0 rounded-3 shadow-sm" role="alert" style="background-color: #FFE4E6; color: #9F1239;">
            <i class="bx bx-error-circle fs-5"></i>
            <div>{{ Session::get('message') }}</div>
          </div>
        @endif

        <form id="formAuthentication" action="/login" method="POST" autocomplete="off">
          @csrf
          <div class="mb-3">
            <label for="email" class="form-label small fw-semibold text-dark">Alamat Email</label>
            <div class="input-group">
              <span class="input-group-text bg-white border-end-0 text-muted"><i class="bx bx-envelope"></i></span>
              <input
                type="email"
                class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror"
                id="email"
                name="email"
                placeholder="nama@klinikfamdentalcare.com"
                value="{{ old('email') }}"
                required
                autofocus
              />
            </div>
            @error('email')
              <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-4 form-password-toggle">
            <label class="form-label small fw-semibold text-dark" for="password">Kata Sandi</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text bg-white border-end-0 text-muted"><i class="bx bx-lock-alt"></i></span>
              <input
                type="password"
                id="password"
                class="form-control border-start-0 ps-0"
                name="password"
                placeholder="••••••••••••"
                required
              />
              <span class="input-group-text cursor-pointer bg-white text-muted"><i class="bx bx-hide"></i></span>
            </div>
          </div>

          <div class="mb-3">
            <button class="btn btn-primary w-100 py-2 fs-6 shadow-sm d-flex align-items-center justify-content-center gap-2" type="submit">
              <i class="bx bx-log-in"></i> Masuk ke Dashboard
            </button>
          </div>
        </form>

        <div class="text-center mt-4 pt-3 border-top">
          <a href="/" class="text-decoration-none small text-muted d-inline-flex align-items-center gap-1 hover-primary">
            <i class="bx bx-arrow-back"></i> Kembali ke Website Utama
          </a>
        </div>

      </div>
    </div>

    <!-- Core JS -->
    <script src="{{ asset('admin/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('admin/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('admin/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('admin/js/main.js') }}"></script>
  </body>
</html>
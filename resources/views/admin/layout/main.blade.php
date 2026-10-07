<!DOCTYPE html>

<!-- =========================================================
* Sneat - Bootstrap 5 HTML Admin Template - Pro | v1.0.0
==============================================================

* Product Page: https://themeselection.com/products/sneat-bootstrap-html-admin-template/
* Created by: ThemeSelection
* License: You must have a valid license purchased in order to legally use the theme for your project.
* Copyright ThemeSelection (https://themeselection.com)

=========================================================
 -->
<!-- beautify ignore:start -->
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>{{ $title }} | Portal Administrator Klinik FAM Dental Care</title>

    <meta name="description" content="Portal Administrasi Resmi Klinik FAM Dental Care Bandung" />

    <!-- Favicon -->
    <link href="{{ asset('assets/img/logo.png') }}" rel="icon">
    <link href="{{ asset('assets/img/logo.png') }}" rel="apple-touch-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{ asset('admin/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('admin/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('admin/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('admin/css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/css/style.css') }}" />

    <!-- UI/UX Pro Max Admin Stylesheet -->
    <link rel="stylesheet" href="{{ asset('admin/css/admin-pro-max.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('admin/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <link rel="stylesheet" href="{{ asset('admin/vendor/libs/apex-charts/apex-charts.css') }}" />

    <!-- Summernote CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

    <!-- Helpers -->
    <script src="{{ asset('admin/vendor/js/helpers.js') }}"></script>

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('admin/js/config.js') }}"></script>
  </head>

  <body>

    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

            <!-- Sidebar -->
            @include('admin.layout.sidebar')

            <div class="layout-page">

                <!-- Navbar -->
                @include('admin.layout.navbar')

                <div class="content-wrapper">

                    <!-- Content -->
                    @yield('content')
                
                    <!-- Footer -->
                    @include('admin.layout.footer')

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
            </div>
    
            <!-- Overlay -->
            <div class="layout-overlay layout-menu-toggle"></div>
        </div>
        <!-- / Layout wrapper -->
    
        <!-- Core JS -->
        <!-- build:js assets/vendor/js/core.js -->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="{{ asset('admin/vendor/libs/popper/popper.js') }}"></script>
        <script src="{{ asset('admin/vendor/js/bootstrap.js') }}"></script>
        <script src="{{ asset('admin/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    
        <script src="{{ asset('admin/vendor/js/menu.js') }}"></script>

        <!-- Summernote JS -->
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    
        <!-- Vendors JS -->
        <script src="{{ asset('admin/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    
        <!-- Main JS -->
        <script src="{{ asset('admin/js/main.js') }}"></script>
    
        <!-- Place this tag in your head or just before your close body tag. -->
        <script async defer src="https://buttons.github.io/buttons.js"></script>

        <!-- Select2 Js -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <!-- Custom Js -->
        <script src="{{ asset('admin/js/addon.js') }}"></script>

        <script>
          @if ($title === 'Beranda')
            var chart_data = [{{ $countervisit[1] ?? 0 }}, {{ $countervisit[2] ?? 0 }}, {{ $countervisit[3] ?? 0 }}, {{ $countervisit[4] ?? 0 }}, {{ $countervisit[5] ?? 0 }}, {{ $countervisit[6] ?? 0 }}, {{ $countervisit[7] ?? 0 }}];
          @endif
        </script>

        <!-- Page JS -->
        <script src="{{ asset('admin/js/dashboards-analytics.js') }}"></script>
        <script src="{{ asset('admin/js/pages-account-settings-account.js') }}"></script>

        <!-- Stacked scripts dari masing-masing view -->
        @stack('scripts')
    </body>
  </html>

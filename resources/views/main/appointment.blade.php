@extends('main.layout.main')

@section('content')

<!-- ======= Appointment Section (UI/UX Pro Max) ======= -->
<section id="appointment" class="py-5" style="padding-top: 130px !important; min-height: 85vh; background: radial-gradient(circle at 50% 10%, #F0FDFA 0%, #F8FAFC 60%);">
  <div class="container" data-aos="fade-up">

    <div class="text-center mb-5">
      <div class="hero-badge mx-auto">
        <i class="bi bi-calendar2-heart"></i>
        <span>Formulir Reservasi Online</span>
      </div>
      <h1 class="h2 fw-bold text-dark mt-2" style="font-family: var(--uipro-font-heading);">
        Buat Janji Temu Pemeriksaan Gigi
      </h1>
      <p class="text-muted mx-auto" style="max-width: 580px; font-size: 15px;">
        Silakan isi data di bawah ini. Tim front office Klinik FAM Dental Care akan segera menghubungi Anda via WhatsApp untuk konfirmasi jadwal.
      </p>
    </div>

    <div class="row justify-content-center">
      <div class="col-lg-8">

        @if (session('sent-message'))
          <div class="alert alert-success d-flex align-items-center gap-2 mb-4 rounded-3 border-0 shadow-sm" role="alert" style="background-color: var(--uipro-accent-light); color: var(--uipro-accent);">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div>{{ session('sent-message') }}</div>
          </div>
        @endif

        @if (session('error'))
          <div class="alert alert-danger d-flex align-items-center gap-2 mb-4 rounded-3 border-0 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <div>{{ session('error') }}</div>
          </div>
        @endif

        <div class="appointment-card">
          <form action="/appointment" method="POST" role="form" enctype="multipart/form-data" autocomplete="off">
            @csrf

            <!-- Section 1: Data Pasien -->
            <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
              <span class="badge rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 12px;">1</span>
              <h5 class="m-0 fs-6 fw-bold text-dark">Data Diri Pasien</h5>
            </div>

            <div class="row g-3 mb-4">
              <div class="col-md-6">
                <label for="nama_pasien" class="form-label-custom">Nama Lengkap Pasien <span class="text-danger">*</span></label>
                <input type="text" id="nama_pasien" name="nama_pasien" class="form-control form-control-custom @error('nama_pasien') is-invalid @enderror" value="{{ old('nama_pasien') }}" required placeholder="Contoh: Budi Santoso">
                @error('nama_pasien')
                  <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6">
                <label for="no_hp_pasien" class="form-label-custom">No. WhatsApp / Telepon <span class="text-danger">*</span></label>
                <input type="tel" id="no_hp_pasien" class="form-control form-control-custom @error('no_hp_pasien') is-invalid @enderror" name="no_hp_pasien" value="{{ old('no_hp_pasien') }}" required placeholder="081234567890">
                @error('no_hp_pasien')
                  <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6">
                <label for="email_pasien" class="form-label-custom">Alamat Email Pasien <span class="text-danger">*</span></label>
                <input type="email" id="email_pasien" class="form-control form-control-custom @error('email_pasien') is-invalid @enderror" name="email_pasien" value="{{ old('email_pasien') }}" required placeholder="email@contoh.com">
                @error('email_pasien')
                  <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6">
                <label for="alamat_pasien" class="form-label-custom">Alamat Domisili <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-custom @error('alamat_pasien') is-invalid @enderror" id="alamat_pasien" name="alamat_pasien" value="{{ old('alamat_pasien') }}" required placeholder="Kota Bandung, Jawa Barat">
                @error('alamat_pasien')
                  <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <!-- Section 2: Jadwal & Dokter -->
            <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
              <span class="badge rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 12px;">2</span>
              <h5 class="m-0 fs-6 fw-bold text-dark">Rencana Kunjungan & Dokter</h5>
            </div>

            <div class="row g-3 mb-4">
              <div class="col-md-6">
                <label for="tanggal_janji" class="form-label-custom">Rencana Tanggal &amp; Jam Kunjungan <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text bg-white border-end-0" style="border-radius: var(--uipro-radius-sm) 0 0 var(--uipro-radius-sm); border-color: var(--uipro-border);">
                    <i class="bi bi-clock-history text-primary"></i>
                  </span>
                  <input type="text" id="tanggal_janji" name="tanggal_janji" class="form-control form-control-custom border-start-0 @error('tanggal_janji') is-invalid @enderror" value="{{ old('tanggal_janji') }}" required placeholder="Pilih tanggal &amp; jam (00:00 - 23:59 WIB)" readonly style="background-color: #ffffff; cursor: pointer;">
                </div>
                <div class="d-flex flex-wrap align-items-center justify-content-between mt-1 gap-1">
                  <small class="text-muted" style="font-size: 11px;"><i class="bi bi-clock me-1"></i>Operasional Klinik: 08:00 – 20:00 WIB (Senin – Sabtu)</small>
                </div>
                <!-- Quick Time Slots (#35) -->
                <div class="mt-2">
                  <span class="text-muted small d-block mb-1" style="font-size: 11px; font-weight: 600;">Rekomendasi Jam Kunjungan:</span>
                  <div class="d-flex flex-wrap gap-1" id="quickSlotContainer">
                    <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2 quick-slot-btn" data-time="09:00" style="font-size: 11px;">09:00 (Pagi)</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2 quick-slot-btn" data-time="11:00" style="font-size: 11px;">11:00 (Siang)</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2 quick-slot-btn" data-time="14:00" style="font-size: 11px;">14:00 (Siang)</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2 quick-slot-btn" data-time="16:30" style="font-size: 11px;">16:30 (Sore)</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2 quick-slot-btn" data-time="18:30" style="font-size: 11px;">18:30 (Malam)</button>
                  </div>
                </div>
                @error('tanggal_janji')
                  <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6">
                <label for="dokter_pilihan" class="form-label-custom">Pilih Dokter Spesialis (Opsional)</label>
                <select name="dokter_pilihan" id="dokter_pilihan" class="form-select form-select-custom">
                  <option value="">-- Rekomendasi Klinik / Bebas --</option>
                  @if (isset($dokter))
                    @foreach ($dokter as $d)
                      <option value="{{ $d->nama_dokter }}" {{ old('dokter_pilihan') == $d->nama_dokter ? 'selected' : '' }}>
                        {{ $d->nama_dokter }} ({{ $d->jadwal_dokter ?? 'Sesuai Jadwal' }})
                      </option>
                    @endforeach
                  @endif
                </select>
              </div>

              <div class="col-12">
                <label for="keluhan_pasien" class="form-label-custom">Keluhan Gigi atau Jenis Perawatan yang Diinginkan <span class="text-danger">*</span></label>
                <textarea class="form-control form-control-custom @error('keluhan_pasien') is-invalid @enderror" id="keluhan_pasien" name="keluhan_pasien" rows="3" required placeholder="Contoh: Scaling karang gigi, konsultasi behel kawat gigi, gigi geraham berlubang, atau bleaching pemutihan gigi...">{{ old('keluhan_pasien') }}</textarea>
                @error('keluhan_pasien')
                  <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
              </div>
              <div style="display:none;" aria-hidden="true">
                <input type="text" name="website_hp" tabindex="-1" autocomplete="off">
              </div>

              <div class="col-12 mt-2">
                <div class="form-check text-start">
                  <input class="form-check-input" type="checkbox" name="persetujuan" id="persetujuan" value="1" checked required>
                  <label class="form-check-label text-muted" for="persetujuan" style="font-size: 13px;">
                    Saya menyetujui data pribadi yang saya isi disimpan dan diproses oleh klinik untuk keperluan reservasi medis &amp; konfirmasi jadwal.
                  </label>
                </div>
              </div>
            </div>

            <div class="pt-3 border-top text-center">
              <button class="appointment-btn btn-lg w-100 justify-content-center py-3 fs-6" type="submit">
                <i class="bi bi-send-check-fill me-1"></i> Kirim Permohonan Janji Temu
              </button>
              <div class="d-flex align-items-center justify-content-center gap-2 mt-3 text-muted" style="font-size: 12px;">
                <i class="bi bi-shield-lock-fill text-success"></i>
                <span>Data pribadi Anda terlindungi dan hanya digunakan untuk keperluan konfirmasi janji medis.</span>
              </div>
            </div>   
          </form>
        </div>

      </div>
    </div>

  </div>
</section><!-- End Appointment Section -->

<!-- Vendor Flatpickr 24-Hour (Indonesia) -->
<link rel="stylesheet" href="{{ asset('assets/vendor/flatpickr.min.css') }}">
<style>
  /* Compact & Scaled-Down Elegant Flatpickr Calendar */
  .flatpickr-calendar {
    font-family: var(--uipro-font-body, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif) !important;
    font-size: 12.5px !important;
    width: 260px !important;
    border-radius: 16px !important;
    box-shadow: 0 16px 36px -8px rgba(15, 23, 42, 0.16), 0 0 0 1px rgba(226, 232, 240, 0.8) !important;
    border: none !important;
    padding: 8px !important;
  }
  .flatpickr-months {
    padding: 4px 6px !important;
    align-items: center !important;
  }
  .flatpickr-months .flatpickr-prev-month, 
  .flatpickr-months .flatpickr-next-month {
    padding: 6px !important;
    height: 28px !important;
    width: 28px !important;
    border-radius: 8px !important;
  }
  .flatpickr-months .flatpickr-prev-month:hover, 
  .flatpickr-months .flatpickr-next-month:hover {
    background: #F1F5F9 !important;
  }
  .flatpickr-months .flatpickr-prev-month svg, 
  .flatpickr-months .flatpickr-next-month svg {
    width: 12px !important;
    height: 12px !important;
  }
  .flatpickr-current-month {
    font-size: 100% !important;
    padding: 2px 0 0 0 !important;
  }
  .flatpickr-current-month .flatpickr-monthDropdown-months {
    font-weight: 700 !important;
    font-size: 13.5px !important;
    color: #0F172A !important;
  }
  .flatpickr-current-month input.cur-year {
    font-weight: 700 !important;
    font-size: 13.5px !important;
    color: #0F172A !important;
  }
  .flatpickr-weekdays {
    height: 24px !important;
    margin-top: 4px !important;
  }
  span.flatpickr-weekday {
    font-size: 11px !important;
    font-weight: 600 !important;
    color: #94A3B8 !important;
  }
  .flatpickr-days {
    width: 244px !important;
  }
  .dayContainer {
    width: 244px !important;
    min-width: 244px !important;
    max-width: 244px !important;
  }
  .flatpickr-day {
    max-width: 34px !important;
    height: 32px !important;
    line-height: 32px !important;
    font-size: 12px !important;
    font-weight: 500 !important;
    border-radius: 8px !important;
    margin: 1px 0 !important;
  }
  .flatpickr-day.selected, 
  .flatpickr-day.startRange, 
  .flatpickr-day.endRange, 
  .flatpickr-day.selected:hover,
  .flatpickr-day.selected:focus {
    background: #0D9488 !important;
    border-color: #0D9488 !important;
    font-weight: 700 !important;
    color: #FFFFFF !important;
  }
  .flatpickr-day:hover {
    background: #F0FDFA !important;
    color: #0D9488 !important;
  }
  .flatpickr-day.today {
    border-color: #14B8A6 !important;
    color: #0D9488 !important;
  }
  .flatpickr-time {
    border-top: 1px solid #E2E8F0 !important;
    margin-top: 6px !important;
    padding-top: 6px !important;
    height: 36px !important;
    line-height: 36px !important;
  }
  .flatpickr-time .numInputWrapper {
    height: 32px !important;
    flex: 1 !important;
  }
  .flatpickr-time input {
    font-size: 14px !important;
    font-weight: 700 !important;
    color: #0F172A !important;
  }
  .flatpickr-time .flatpickr-time-separator {
    font-size: 14px !important;
    font-weight: 700 !important;
    color: #64748B !important;
  }
  .flatpickr-time input:hover, 
  .flatpickr-time input:focus {
    background: #F0FDFA !important;
    color: #0D9488 !important;
  }
  .flatpickr-am-pm {
    display: none !important;
  }
</style>
<script src="{{ asset('assets/vendor/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/vendor/flatpickr-id.js') }}"></script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    var fp = flatpickr("#tanggal_janji", {
      enableTime: true,
      time_24hr: true,
      minDate: "today",
      minTime: "08:00",
      maxTime: "20:00",
      dateFormat: "Y-m-d H:i:s",
      altInput: true,
      altFormat: "d F Y - H:i \\W\\I\\B",
      locale: "id",
      defaultHour: 9,
      defaultMinute: 0,
      minuteIncrement: 15,
      allowInput: false
    });

    document.querySelectorAll(".quick-slot-btn").forEach(function (btn) {
      btn.addEventListener("click", function () {
        var timeParts = this.getAttribute("data-time").split(":");
        var hour = parseInt(timeParts[0], 10);
        var min = parseInt(timeParts[1], 10);

        var baseDate = (fp.selectedDates && fp.selectedDates.length > 0) ? new Date(fp.selectedDates[0]) : new Date();
        baseDate.setHours(hour, min, 0, 0);

        fp.setDate(baseDate, true);

        document.querySelectorAll(".quick-slot-btn").forEach(function (b) {
          b.classList.remove("btn-primary", "text-white");
          b.classList.add("btn-outline-secondary");
        });
        btn.classList.remove("btn-outline-secondary");
        btn.classList.add("btn-primary", "text-white");
      });
    });
  });
</script>
@endsection
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
                <label for="tanggal_janji" class="form-label-custom">Rencana Tanggal & Jam Janji Temu <span class="text-danger">*</span></label>
                <input type="datetime-local" id="tanggal_janji" name="tanggal_janji" class="form-control form-control-custom @error('tanggal_janji') is-invalid @enderror" value="{{ old('tanggal_janji') }}" required>
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

@endsection
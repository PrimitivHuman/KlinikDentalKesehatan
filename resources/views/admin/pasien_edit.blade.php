@extends('admin.layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Breadcrumbs & Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center py-3 mb-4 gap-3">
        <h4 class="fw-bold mb-0">
            <span class="text-muted fw-light">
                <a href="/admin-area" class="a-breadcrumbs">Beranda</a> /
                <a href="/admin-area/pasien" class="a-breadcrumbs">Data Pasien</a> /
            </span> Form Pembayaran & Rekam Medis
        </h4>
        <a href="/admin-area/pasien" class="btn btn-outline-secondary d-inline-flex align-items-center gap-1">
            <i class="bx bx-arrow-back"></i>
            <span>Kembali</span>
        </a>
    </div>

    @include('admin.layout.alert')

    <div class="row">
        <div class="col-12 col-xl-10 mx-auto">
            <div class="card border-0 shadow-sm" style="border-radius: 14px; overflow: hidden;">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title mb-0 fw-bold text-dark">
                        <i class="bx bx-receipt text-primary me-2"></i>Rincian Pasien & Tindakan Medis
                    </h5>
                </div>
                <form action="/admin-area/pasien/edit/update" method="POST">
                    @csrf
                    <input type="hidden" name="id_pasien" value="{{ $pasien->id_pasien }}">
                    
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-muted text-uppercase small mb-3">Informasi Pasien</h6>
                        <div class="row g-3 mb-4">
                            <!-- Nama Pasien -->
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold text-dark">Nama Pasien</label>
                                <input type="text" name="nama_pasien" class="form-control" 
                                       value="{{ old('nama_pasien', $pasien->nama_pasien) }}" required>
                                @error('nama_pasien')
                                <small class="text-danger"><i class="bx bx-error-circle"></i> {{ $message }}</small>
                                @enderror
                            </div>

                            <!-- No HP Pasien -->
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold text-dark">Nomor WhatsApp / HP</label>
                                <input type="text" name="no_hp_pasien" class="form-control" 
                                       value="{{ old('no_hp_pasien', $pasien->no_hp_pasien) }}" required>
                                @error('no_hp_pasien')
                                <small class="text-danger"><i class="bx bx-error-circle"></i> {{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Tanggal & Waktu Janji -->
                            @php
                                $dt = null;
                                try {
                                    $dt = $pasien->tanggal_janji ? \Carbon\Carbon::parse($pasien->tanggal_janji) : null;
                                } catch (\Exception $e) {
                                    $dt = null;
                                }
                                $initialVal = old('tanggal_janji', $dt ? $dt->format('Y-m-d H:i:s') : date('Y-m-d 09:00:00'));
                                $defaultHour = $dt ? (int) $dt->format('H') : 9;
                                $defaultMinute = $dt ? (int) $dt->format('i') : 0;
                            @endphp
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold text-dark" for="tanggal_janji">
                                    Tanggal & Waktu Janji Temu <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted">
                                        <i class="bx bx-calendar-event"></i>
                                    </span>
                                    <input type="text" 
                                           id="tanggal_janji" 
                                           name="tanggal_janji" 
                                           class="form-control border-start-0 ps-0 bg-white @error('tanggal_janji') is-invalid @enderror" 
                                           value="{{ $initialVal }}" 
                                           placeholder="Pilih tanggal & waktu janji..." 
                                           required>
                                </div>
                                <div class="form-text text-muted" style="font-size: 11px;">Format waktu 24 jam (00:00 - 23:59 WIB)</div>
                                @error('tanggal_janji')
                                <small class="text-danger d-block mt-1"><i class="bx bx-error-circle"></i> {{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Dokter Spesialis / Pemeriksa (Saran 1) -->
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold text-dark" for="id_dokter">
                                    Dokter Spesialis / Pemeriksa
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted">
                                        <i class="bx bx-user-pin"></i>
                                    </span>
                                    <select name="id_dokter" id="id_dokter" class="form-select border-start-0 ps-0 @error('id_dokter') is-invalid @enderror">
                                        <option value="">-- Dokter Umum / Dokter Jaga --</option>
                                        @if(isset($dokters) && count($dokters) > 0)
                                            @foreach($dokters as $dok)
                                                <option value="{{ $dok->id_dokter }}" 
                                                        data-nama="{{ $dok->nama_dokter }}"
                                                        {{ (old('id_dokter', $pasien->id_dokter) == $dok->id_dokter || old('dokter_pilihan', $pasien->dokter_pilihan) == $dok->nama_dokter) ? 'selected' : '' }}>
                                                    {{ $dok->nama_dokter }} ({{ $dok->id_dokter }})
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <input type="hidden" name="dokter_pilihan" id="dokter_pilihan" value="{{ old('dokter_pilihan', $pasien->dokter_pilihan) }}">
                                <div class="form-text text-muted" style="font-size: 11px;">Pilih dokter yang bertugas menangani pasien ini.</div>
                                @error('id_dokter')
                                <small class="text-danger d-block mt-1"><i class="bx bx-error-circle"></i> {{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Email Pasien -->
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold text-dark">Email Pasien</label>
                                <input type="email" name="email_pasien" class="form-control" 
                                       value="{{ old('email_pasien', $pasien->email_pasien) }}" required>
                                @error('email_pasien')
                                <small class="text-danger"><i class="bx bx-error-circle"></i> {{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Alamat Pasien -->
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold text-dark">Alamat Tempat Tinggal</label>
                                <input type="text" name="alamat_pasien" class="form-control" 
                                       value="{{ old('alamat_pasien', $pasien->alamat_pasien) }}" required>
                                @error('alamat_pasien')
                                <small class="text-danger"><i class="bx bx-error-circle"></i> {{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Keluhan Pasien -->
                            <div class="col-12">
                                <label class="form-label fw-semibold text-dark">Keluhan Pasien</label>
                                <textarea name="keluhan_pasien" class="form-control" rows="3" required>{{ old('keluhan_pasien', $pasien->keluhan_pasien) }}</textarea>
                                @error('keluhan_pasien')
                                <small class="text-danger"><i class="bx bx-error-circle"></i> {{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <h6 class="fw-bold text-muted text-uppercase small mb-3">Tindakan Klinis & Administrasi</h6>
                        <div class="row g-3">
                            <!-- Pilihan Cepat Layanan (Saran 3) -->
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold text-dark" for="select_layanan">
                                    Pilihan Cepat Layanan (Preset)
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted">
                                        <i class="bx bx-plus-medical text-primary"></i>
                                    </span>
                                    <select id="select_layanan" class="form-select border-start-0 ps-0">
                                        <option value="">-- Pilih Layanan untuk Isi Cepat --</option>
                                        @if(isset($layanans) && count($layanans) > 0)
                                            @foreach($layanans as $lay)
                                                <option value="{{ $lay->id_layanan }}" 
                                                        data-nama="{{ $lay->nama_layanan }}" 
                                                        data-harga="{{ $lay->harga_mulai ?? 0 }}">
                                                    {{ $lay->nama_layanan }} @if($lay->harga_mulai) (Mulai Rp {{ number_format($lay->harga_mulai, 0, ',', '.') }}) @endif
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-text text-muted" style="font-size: 11px;">
                                    Pilih layanan klinik untuk otomatis menyalin ke tindakan &amp; estimasi biaya.
                                </div>
                            </div>

                            <!-- Total Biaya -->
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold text-dark">Total Biaya Perawatan (Rp)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light fw-bold text-muted">Rp</span>
                                    <input type="number" 
                                           id="total_harga_pasien"
                                           name="total_harga_pasien" 
                                           class="form-control" 
                                           placeholder="0" 
                                           value="{{ old('total_harga_pasien', $pasien->total_harga_pasien) }}">
                                </div>
                                <div class="form-text text-muted" style="font-size: 11px;">Masukkan nominal total tanpa titik atau koma.</div>
                                @error('total_harga_pasien')
                                <small class="text-danger d-block mt-1"><i class="bx bx-error-circle"></i> {{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Tindakan Pasien -->
                            <div class="col-12">
                                <label class="form-label fw-semibold text-dark">Tindakan Medis / Perawatan yang Diberikan</label>
                                <textarea id="tindakan_pasien" 
                                          name="tindakan_pasien" 
                                          class="form-control" 
                                          rows="3" 
                                          placeholder="Contoh: Pembersihan Karang Gigi (Scaling), Penambalan Gigi Molar...">{{ old('tindakan_pasien', $pasien->tindakan_pasien) }}</textarea>
                                @error('tindakan_pasien')
                                <small class="text-danger"><i class="bx bx-error-circle"></i> {{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="card-footer bg-light border-top py-3 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <a href="/admin-area/pasien" class="btn btn-outline-secondary">
                                Batal
                            </a>
                            @php
                                $cleanPhone = preg_replace('/\D/', '', $pasien->no_hp_pasien ?? '');
                                if (str_starts_with($cleanPhone, '0')) {
                                    $cleanPhone = '62' . substr($cleanPhone, 1);
                                }
                            @endphp
                            @if(!empty($cleanPhone))
                            <a href="https://wa.me/{{ $cleanPhone }}?text=Halo%20Bpk%2FIbu%20{{ urlencode($pasien->nama_pasien) }}%2C%20kami%20dari%20Klinik%20FAM%20Dental%20Care%20ingin%20mengonfirmasi%20jadwal%20kunjungan%20Anda." 
                               target="_blank" class="btn btn-outline-success d-inline-flex align-items-center gap-1" title="Kirim Pesan WhatsApp">
                                <i class="bx bxl-whatsapp"></i> Chat WhatsApp
                            </a>
                            @endif
                        </div>
                        <button class="btn btn-primary d-inline-flex align-items-center gap-1 shadow-sm" type="submit">
                            <i class="bx bx-save"></i>
                            <span>Simpan Rekam & Pembayaran</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Riwayat Kunjungan Sebelumnya (#7) -->
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-label-info p-2">
                            <i class="bx bx-history fs-5"></i>
                        </span>
                        <div>
                            <h6 class="card-title mb-0 fw-bold text-dark">Riwayat Rekam Medis & Kunjungan Pasien</h6>
                            <small class="text-muted">Kunjungan lampau berdasarkan kontak yang sama (No. HP: {{ $pasien->no_hp_pasien }})</small>
                        </div>
                    </div>
                    <span class="badge bg-light text-dark border">
                        Total: {{ isset($riwayat_kunjungan) ? count($riwayat_kunjungan) : 0 }} Riwayat
                    </span>
                </div>
                <div class="card-body p-0">
                    @if(isset($riwayat_kunjungan) && count($riwayat_kunjungan) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr class="small text-muted">
                                        <th class="ps-4">Tanggal Kunjungan</th>
                                        <th>Dokter</th>
                                        <th>Keluhan</th>
                                        <th>Tindakan Medis</th>
                                        <th>Biaya</th>
                                        <th>Status</th>
                                        <th class="text-end pe-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($riwayat_kunjungan as $rk)
                                        <tr>
                                            <td class="ps-4 fw-semibold text-dark">
                                                {{ $rk->tanggal_janji ? \Carbon\Carbon::parse($rk->tanggal_janji)->translatedFormat('d M Y, H:i') : '-' }}
                                            </td>
                                            <td>
                                                <span class="small text-dark">{{ $rk->dokter_pilihan ?? ($rk->dokter->nama_dokter ?? '-') }}</span>
                                            </td>
                                            <td><span class="small text-muted">{{ Str::limit($rk->keluhan_pasien, 25) }}</span></td>
                                            <td><span class="small fw-semibold text-dark">{{ $rk->tindakan_pasien ?? '-' }}</span></td>
                                            <td>
                                                <span class="small fw-bold text-success">
                                                    {{ $rk->total_harga_pasien ? 'Rp ' . number_format($rk->total_harga_pasien, 0, ',', '.') : '-' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-label-{{ $rk->status === 'completed' ? 'success' : ($rk->status === 'confirmed' ? 'info' : ($rk->status === 'cancelled' ? 'danger' : 'warning')) }}">
                                                    {{ ucfirst($rk->status) }}
                                                </span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <a href="/admin-area/pasien/edit/{{ Crypt::encrypt($rk->id_pasien) }}" class="btn btn-sm btn-outline-primary py-0 px-2">
                                                    Buka
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="bx bx-folder-open fs-2 text-muted opacity-50 mb-1"></i>
                            <p class="mb-0 small">Belum ada riwayat kunjungan lampau untuk pasien ini (Pasien Baru).</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="{{ asset('assets/vendor/flatpickr.min.css') }}">
<style>
  /* Flatpickr 24-Jam & Desain Selaras Admin Pro Max */
  .flatpickr-calendar {
    font-family: var(--admin-font-body, 'Inter', sans-serif) !important;
    font-size: 13px !important;
    border-radius: 14px !important;
    box-shadow: 0 16px 36px -8px rgba(15, 23, 42, 0.16), 0 0 0 1px rgba(226, 232, 240, 0.8) !important;
    border: none !important;
    padding: 8px !important;
  }
  .flatpickr-months {
    padding: 4px 6px !important;
    align-items: center !important;
  }
  .flatpickr-time {
    border-top: 1px solid #E2E8F0 !important;
    margin-top: 6px !important;
    padding-top: 6px !important;
    height: 38px !important;
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
  .flatpickr-day.selected, 
  .flatpickr-day.startRange, 
  .flatpickr-day.endRange {
    background: #0D9488 !important;
    border-color: #0D9488 !important;
  }
  .flatpickr-day:hover {
    background: #CCFBF1 !important;
    color: #0F766E !important;
  }
  .flatpickr-am-pm {
    display: none !important;
  }
</style>
<script src="{{ asset('assets/vendor/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/vendor/flatpickr-id.js') }}"></script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    flatpickr("#tanggal_janji", {
      enableTime: true,
      time_24hr: true,
      dateFormat: "Y-m-d H:i:s",
      altInput: true,
      altFormat: "d F Y - H:i \\W\\I\\B",
      locale: "id",
      defaultHour: {{ $defaultHour }},
      defaultMinute: {{ $defaultMinute }},
      minuteIncrement: 5,
      allowInput: false
    });

    // Saran 1: Sinkronisasi nama dokter saat dropdown dipilih
    const selectDokter = document.getElementById('id_dokter');
    const inputDokterPilihan = document.getElementById('dokter_pilihan');
    if (selectDokter && inputDokterPilihan) {
      selectDokter.addEventListener('change', function () {
        const opt = selectDokter.options[selectDokter.selectedIndex];
        if (opt && opt.value) {
          inputDokterPilihan.value = opt.getAttribute('data-nama') || opt.text;
        } else {
          inputDokterPilihan.value = '';
        }
      });
    }

    // Saran 3: Pilihan Cepat Layanan (Preset)
    const selectLayanan = document.getElementById('select_layanan');
    const textareaTindakan = document.getElementById('tindakan_pasien');
    const inputHarga = document.getElementById('total_harga_pasien');

    if (selectLayanan && textareaTindakan && inputHarga) {
      selectLayanan.addEventListener('change', function () {
        const opt = selectLayanan.options[selectLayanan.selectedIndex];
        if (!opt || !opt.value) return;

        const namaLayanan = opt.getAttribute('data-nama');
        const harga = opt.getAttribute('data-harga');

        // Isi / tambahkan tindakan medis
        const currentVal = textareaTindakan.value.trim();
        if (!currentVal || currentVal === '-') {
          textareaTindakan.value = namaLayanan;
        } else if (!currentVal.toLowerCase().includes(namaLayanan.toLowerCase())) {
          textareaTindakan.value = currentVal + ', ' + namaLayanan;
        }

        // Isi estimasi biaya jika biaya masih kosong atau 0
        if ((!inputHarga.value || inputHarga.value === '0') && harga && parseInt(harga) > 0) {
          inputHarga.value = harga;
        }
      });
    }
  });
</script>
@endpush
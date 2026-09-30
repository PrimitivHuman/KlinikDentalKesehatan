@extends('admin.layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Breadcrumbs & Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold mb-1 text-dark">Form Pembayaran & Rekam Medis</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/admin-area" class="text-muted">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="/admin-area/pasien" class="text-muted">Data Pasien</a></li>
                    <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page">{{ $pasien->nama_pasien }}</li>
                </ol>
            </nav>
        </div>
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

                            <!-- Tanggal Janji -->
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold text-dark">Tanggal Janji Temu</label>
                                <input type="date" name="tanggal_janji" class="form-control" 
                                       value="{{ old('tanggal_janji', $pasien->tanggal_janji) }}" required>
                                @error('tanggal_janji')
                                <small class="text-danger"><i class="bx bx-error-circle"></i> {{ $message }}</small>
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
                            <div class="col-12">
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
                            <!-- Tindakan Pasien -->
                            <div class="col-12">
                                <label class="form-label fw-semibold text-dark">Tindakan Medis / Perawatan yang Diberikan</label>
                                <textarea name="tindakan_pasien" class="form-control" rows="3" placeholder="Contoh: Pembersihan Karang Gigi (Scaling), Penambalan Gigi Molar...">{{ old('tindakan_pasien', $pasien->tindakan_pasien) }}</textarea>
                                @error('tindakan_pasien')
                                <small class="text-danger"><i class="bx bx-error-circle"></i> {{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Total Biaya -->
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold text-dark">Total Biaya Perawatan (Rp)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light fw-bold">Rp</span>
                                    <input type="number" name="total_harga_pasien" class="form-control" placeholder="0" 
                                           value="{{ old('total_harga_pasien', $pasien->total_harga_pasien) }}">
                                </div>
                                <small class="text-muted">Masukkan nominal total tanpa titik atau koma.</small>
                                @error('total_harga_pasien')
                                <small class="text-danger d-block"><i class="bx bx-error-circle"></i> {{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="card-footer bg-light border-top py-3 d-flex justify-content-between align-items-center">
                        <a href="/admin-area/pasien" class="btn btn-outline-secondary">
                            Batal
                        </a>
                        <button class="btn btn-primary d-inline-flex align-items-center gap-1 shadow-sm" type="submit">
                            <i class="bx bx-save"></i>
                            <span>Simpan Rekam & Pembayaran</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
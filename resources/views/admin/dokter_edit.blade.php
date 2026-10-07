@extends('admin.layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Breadcrumbs & Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center py-3 mb-4 gap-3">
        <h4 class="fw-bold mb-0">
            <span class="text-muted fw-light">
                <a href="/admin-area" class="a-breadcrumbs">Beranda</a> /
                <a href="/admin-area/dokter" class="a-breadcrumbs">Data Dokter</a> /
            </span> Edit Data Dokter
        </h4>
        <a href="/admin-area/dokter" class="btn btn-outline-secondary d-inline-flex align-items-center gap-1">
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
                        <i class="bx bx-edit-alt text-primary me-2"></i>Formulir Perubahan Data Dokter
                    </h5>
                </div>
                <form action="/admin-area/dokter/edit/update" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <!-- ID Dokter (Read Only) -->
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold text-dark">ID Dokter</label>
                                <input type="text" name="id_dokter" readonly class="form-control bg-light font-monospace" 
                                       value="{{ old('id_dokter', $dokter->id_dokter) }}" required>
                                <small class="text-muted">ID unik dihasilkan otomatis oleh sistem.</small>
                            </div>

                            <!-- Nama Dokter -->
                            <div class="col-12 col-md-8">
                                <label class="form-label fw-semibold text-dark">Nama Lengkap & Gelar <span class="text-danger">*</span></label>
                                <input type="text" name="nama_dokter" class="form-control" placeholder="Contoh: drg. Sarah Jenkins, Sp.KG" 
                                       value="{{ old('nama_dokter', $dokter->nama_dokter) }}" required>
                                @error('nama_dokter')
                                <small class="text-danger"><i class="bx bx-error-circle"></i> {{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Nomor Telepon -->
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold text-dark">Nomor Telepon / WhatsApp <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bx bx-phone"></i></span>
                                    <input type="text" name="no_hp_dokter" class="form-control" placeholder="08xxxxxxxxxx" 
                                           value="{{ old('no_hp_dokter', $dokter->no_hp_dokter) }}" required>
                                </div>
                                @error('no_hp_dokter')
                                <small class="text-danger"><i class="bx bx-error-circle"></i> {{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold text-dark">Alamat Email <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bx bx-envelope"></i></span>
                                    <input type="email" name="email_dokter" class="form-control" placeholder="dokter@klinikdental.com" 
                                           value="{{ old('email_dokter', $dokter->email_dokter) }}" required>
                                </div>
                                @error('email_dokter')
                                <small class="text-danger"><i class="bx bx-error-circle"></i> {{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Jadwal Praktik -->
                            <div class="col-12">
                                <label class="form-label fw-semibold text-dark">Jadwal Praktik Dokter <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bx bx-calendar"></i></span>
                                    <input type="text" name="jadwal_dokter" class="form-control" placeholder="Contoh: Senin - Kamis (09:00 - 15:00)" 
                                           value="{{ old('jadwal_dokter', $dokter->jadwal_dokter) }}" required>
                                </div>
                                @error('jadwal_dokter')
                                <small class="text-danger"><i class="bx bx-error-circle"></i> {{ $message }}</small>
                                @enderror
                            </div>

                            <!-- STR & SIP -->
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold text-dark">Surat Tanda Registrasi (STR) <span class="text-danger">*</span></label>
                                <input type="text" name="str_dokter" class="form-control font-monospace" placeholder="Nomor STR aktif" 
                                       value="{{ old('str_dokter', $dokter->str_dokter) }}" required>
                                @error('str_dokter')
                                <small class="text-danger"><i class="bx bx-error-circle"></i> {{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold text-dark">Surat Izin Praktik (SIP) <span class="text-danger">*</span></label>
                                <input type="text" name="sip_dokter" class="form-control font-monospace" placeholder="Nomor SIP aktif" 
                                       value="{{ old('sip_dokter', $dokter->sip_dokter) }}" required>
                                @error('sip_dokter')
                                <small class="text-danger"><i class="bx bx-error-circle"></i> {{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Foto Profil -->
                            <div class="col-12 mt-4 pt-3 border-top">
                                <label class="form-label fw-semibold text-dark mb-3">Foto Profil Dokter</label>
                                <div class="d-flex flex-column flex-sm-row align-items-center gap-4 p-3 bg-light rounded-3">
                                    @if(!empty($dokter->images) && file_exists(public_path('img/dokter/'.$dokter->images)))
                                        <img src="{{ asset('/img/dokter/'.$dokter->images) }}" alt="{{ $dokter->nama_dokter }}" 
                                             class="rounded-circle shadow-sm object-fit-cover" 
                                             height="110" width="110" id="uploadedAvatar" 
                                             style="border: 3px solid #fff;" />
                                    @else
                                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-white shadow-sm text-primary fw-bold"
                                             style="width: 110px; height: 110px; font-size: 2rem; border: 3px solid #fff;" id="uploadedAvatar">
                                            {{ strtoupper(substr($dokter->nama_dokter ?? 'D', 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="button-wrapper">
                                        <label for="upload" class="btn btn-primary btn-sm me-2 mb-2" tabindex="0">
                                            <i class="bx bx-upload me-1"></i> Unggah Foto Baru
                                            <input name="foto" type="file" id="upload" class="account-file-input" hidden
                                                   accept="image/png, image/jpeg, image/webp" />
                                        </label>
                                        <p class="text-muted small mb-0">Format JPG, PNG, atau WEBP. Ukuran maksimal 2MB. Kosongkan jika tidak ingin mengubah foto.</p>
                                        @error('foto')
                                        <small class="text-danger d-block mt-1"><i class="bx bx-error-circle"></i> {{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="card-footer bg-light border-top py-3 d-flex justify-content-between align-items-center">
                        <a href="/admin-area/dokter" class="btn btn-outline-secondary">
                            Batal
                        </a>
                        <button class="btn btn-primary d-inline-flex align-items-center gap-1 shadow-sm" type="submit">
                            <i class="bx bx-save"></i>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
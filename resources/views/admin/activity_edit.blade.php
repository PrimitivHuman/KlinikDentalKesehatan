@extends('admin.layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold mb-1 text-dark">Edit Agenda Kegiatan</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/admin-area" class="text-muted">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="/admin-area/kegiatan" class="text-muted">Agenda Kegiatan</a></li>
                    <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page">{{ $activity->judul_kegiatan }}</li>
                </ol>
            </nav>
        </div>
        <a href="/admin-area/kegiatan" class="btn btn-outline-secondary d-inline-flex align-items-center gap-1">
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
                        <i class="bx bx-edit-alt text-primary me-2"></i>Formulir Perubahan Agenda Kegiatan
                    </h5>
                </div>
                <form action="/admin-area/kegiatan/edit/update" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id_kegiatan" value="{{ Crypt::encrypt($activity->id_kegiatan) }}">
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-12 col-md-8">
                                <label for="judul_kegiatan" class="form-label fw-semibold text-dark">Judul Kegiatan <span class="text-danger">*</span></label>
                                <input type="text" id="judul_kegiatan" name="judul_kegiatan" class="form-control" required value="{{ old('judul_kegiatan', $activity->judul_kegiatan) }}">
                                @error('judul_kegiatan')
                                <small class="text-danger"><i class="bx bx-error-circle"></i> {{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="tgl_kegiatan" class="form-label fw-semibold text-dark">Tanggal Pelaksanaan <span class="text-danger">*</span></label>
                                <input type="date" id="tgl_kegiatan" name="tgl_kegiatan" class="form-control" required value="{{ old('tgl_kegiatan', $activity->tgl_kegiatan) }}">
                                @error('tgl_kegiatan')
                                <small class="text-danger"><i class="bx bx-error-circle"></i> {{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="foto" class="form-label fw-semibold text-dark">Foto Dokumentasi</label>
                                <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3 mb-2">
                                    @if(!empty($activity->images) && file_exists(public_path('img/activity/'.$activity->images)))
                                        <img src="{{ asset('img/activity/'.$activity->images) }}" alt="foto" height="80" class="rounded shadow-sm object-fit-cover">
                                    @endif
                                    <div class="flex-grow-1">
                                        <input type="file" id="foto" name="foto" class="form-control" accept="image/png, image/jpeg, image/webp">
                                        <small class="text-muted d-block mt-1">Format: JPG, PNG, WEBP. Maksimal 2MB. Kosongkan jika tidak ingin diubah.</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="summernote" class="form-label fw-semibold text-dark">Deskripsi Kegiatan <span class="text-danger">*</span></label>
                                <textarea name="deskripsi_kegiatan" id="summernote" class="form-control" rows="6" required>{{ old('deskripsi_kegiatan', $activity->deskripsi_kegiatan) }}</textarea>
                                @error('deskripsi_kegiatan')
                                <small class="text-danger"><i class="bx bx-error-circle"></i> {{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light border-top py-3 d-flex justify-content-between align-items-center">
                        <a href="/admin-area/kegiatan" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1 shadow-sm">
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
@extends('admin.layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">
            <a href="/admin-area" class="a-breadcrumbs">Beranda</a> /
            <a href="/admin-area/berita" class="a-breadcrumbs">Berita</a> /
        </span> Artikel Baru
    </h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Tambah Artikel / Berita Klinik</h5>
                <form action="/admin-area/berita/submit" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-9">
                                <label for="judul" class="form-label">Judul Artikel <span class="text-danger">*</span></label>
                                <input type="text" id="judul" name="judul" class="form-control @error('judul') is-invalid @enderror"
                                    required placeholder="Contoh: Tips Merawat Kesehatan Gigi" value="{{ old('judul') }}">
                                @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3 col-md-3">
                                <label for="status" class="form-label">Status Publikasi</label>
                                <select id="status" name="status" class="form-select">
                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                                </select>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="tgl_terbit" class="form-label">Tanggal Terbit</label>
                                <input type="date" id="tgl_terbit" name="tgl_terbit" class="form-control"
                                    value="{{ old('tgl_terbit', date('Y-m-d')) }}">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="foto" class="form-label">Gambar Sampul (Opsional)</label>
                                <input type="file" id="foto" name="foto" class="form-control @error('foto') is-invalid @enderror"
                                    accept="image/png, image/jpeg, image/webp">
                                <small class="text-muted">Format: JPG, PNG, WEBP. Maks 2MB.</small>
                                @error('foto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3 col-md-12">
                                <label for="isi" class="form-label">Isi Artikel <span class="text-danger">*</span></label>
                                <textarea name="isi" id="summernote" class="form-control @error('isi') is-invalid @enderror"
                                    rows="10">{{ old('isi') }}</textarea>
                                @error('isi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bx bx-save me-1"></i> Simpan Artikel
                            </button>
                            <a href="/admin-area/berita" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

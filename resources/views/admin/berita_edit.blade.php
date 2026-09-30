@extends('admin.layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">
            <a href="/admin-area" class="a-breadcrumbs">Beranda</a> /
            <a href="/admin-area/berita" class="a-breadcrumbs">Berita</a> /
        </span> Edit Artikel
    </h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Edit Artikel — {{ Str::limit($berita->judul, 60) }}</h5>
                <form action="/admin-area/berita/edit/update" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id_berita" value="{{ $berita->id_berita }}">
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-9">
                                <label for="judul" class="form-label">Judul Artikel <span class="text-danger">*</span></label>
                                <input type="text" id="judul" name="judul" class="form-control"
                                    required value="{{ old('judul', $berita->judul) }}">
                            </div>
                            <div class="mb-3 col-md-3">
                                <label for="status" class="form-label">Status Publikasi</label>
                                <select id="status" name="status" class="form-select">
                                    <option value="draft" {{ $berita->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ $berita->status == 'published' ? 'selected' : '' }}>Published</option>
                                </select>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="tgl_terbit" class="form-label">Tanggal Terbit</label>
                                <input type="date" id="tgl_terbit" name="tgl_terbit" class="form-control"
                                    value="{{ old('tgl_terbit', $berita->tgl_terbit?->format('Y-m-d')) }}">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="foto" class="form-label">Ganti Gambar Sampul (Opsional)</label>
                                @if($berita->images)
                                <div class="mb-2">
                                    <img src="{{ asset('img/berita/'.$berita->images) }}" height="60" class="rounded" alt="Gambar saat ini">
                                    <small class="text-muted d-block">Gambar saat ini. Upload baru untuk mengganti.</small>
                                </div>
                                @endif
                                <input type="file" id="foto" name="foto" class="form-control" accept="image/png, image/jpeg, image/webp">
                                <small class="text-muted">Format: JPG, PNG, WEBP. Maks 2MB.</small>
                            </div>
                            <div class="mb-3 col-md-12">
                                <label for="isi" class="form-label">Isi Artikel <span class="text-danger">*</span></label>
                                <textarea name="isi" id="summernote" class="form-control" rows="10">{{ old('isi', $berita->isi) }}</textarea>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bx bx-save me-1"></i> Simpan Perubahan
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

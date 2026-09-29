@extends('admin.layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><a href="/admin-area" class="a-breadcrumbs">Beranda</a> / <a href="/admin-area/kegiatan" class="a-breadcrumbs">Agenda Kegiatan</a> / </span> Data Baru</h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Tambah Agenda Kegiatan Baru</h5>
                <form action="/admin-area/kegiatan/submit" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-8">
                                <label for="judul_kegiatan" class="form-label">Judul Kegiatan</label>
                                <input type="text" id="judul_kegiatan" name="judul_kegiatan" class="form-control" required placeholder="Contoh: Bakti Sosial Kesehatan Gigi" value="{{ old('judul_kegiatan') }}">
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="tgl_kegiatan" class="form-label">Tanggal Pelaksanaan</label>
                                <input type="date" id="tgl_kegiatan" name="tgl_kegiatan" class="form-control" required value="{{ old('tgl_kegiatan', date('Y-m-d')) }}">
                            </div>
                            <div class="mb-3 col-md-12">
                                <label for="foto" class="form-label">Foto Dokumentasi Kegiatan</label>
                                <input type="file" id="foto" name="foto" class="form-control" required accept="image/png, image/jpeg, image/webp">
                                <small class="text-muted">Format: JPG, PNG, WEBP. Maksimal 2MB.</small>
                            </div>
                            <div class="mb-3 col-md-12">
                                <label for="deskripsi_kegiatan" class="form-label">Deskripsi Kegiatan</label>
                                <textarea name="deskripsi_kegiatan" id="summernote" class="form-control" rows="5" required placeholder="Tuliskan detail kegiatan di sini...">{{ old('deskripsi_kegiatan') }}</textarea>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary me-2">Simpan Kegiatan</button>
                            <a href="/admin-area/kegiatan" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
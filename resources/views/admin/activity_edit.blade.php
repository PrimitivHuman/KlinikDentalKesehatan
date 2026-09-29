@extends('admin.layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><a href="/admin-area" class="a-breadcrumbs">Beranda</a> / <a href="/admin-area/kegiatan" class="a-breadcrumbs">Agenda Kegiatan</a> / </span> Edit Data</h4>

    @include('admin.layout.alert')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Edit Agenda Kegiatan</h5>
                <form action="/admin-area/kegiatan/edit/update" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id_kegiatan" value="{{ Crypt::encrypt($activity[0]->id_kegiatan) }}">
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-8">
                                <label for="judul_kegiatan" class="form-label">Judul Kegiatan</label>
                                <input type="text" id="judul_kegiatan" name="judul_kegiatan" class="form-control" required value="{{ old('judul_kegiatan', $activity[0]->judul_kegiatan) }}">
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="tgl_kegiatan" class="form-label">Tanggal Pelaksanaan</label>
                                <input type="date" id="tgl_kegiatan" name="tgl_kegiatan" class="form-control" required value="{{ old('tgl_kegiatan', $activity[0]->tgl_kegiatan) }}">
                            </div>
                            <div class="mb-3 col-md-12">
                                <label for="foto" class="form-label">Foto Dokumentasi (Kosongkan jika tidak ingin diubah)</label>
                                <div class="d-flex align-items-center gap-3 mb-2">
                                    <img src="{{ asset('img/activity/'.$activity[0]->images) }}" alt="foto" height="80" class="rounded">
                                    <input type="file" id="foto" name="foto" class="form-control" accept="image/png, image/jpeg, image/webp">
                                </div>
                                <small class="text-muted">Format: JPG, PNG, WEBP. Maksimal 2MB.</small>
                            </div>
                            <div class="mb-3 col-md-12">
                                <label for="deskripsi_kegiatan" class="form-label">Deskripsi Kegiatan</label>
                                <textarea name="deskripsi_kegiatan" id="summernote" class="form-control" rows="5" required>{{ old('deskripsi_kegiatan', $activity[0]->deskripsi_kegiatan) }}</textarea>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary me-2">Simpan Perubahan</button>
                            <a href="/admin-area/kegiatan" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
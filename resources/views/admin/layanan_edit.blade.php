@extends('admin.layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">
            <a href="/admin-area" class="a-breadcrumbs">Beranda</a> /
            <a href="/admin-area/layanan" class="a-breadcrumbs">Layanan</a> /
        </span> Edit Layanan
    </h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Edit — {{ $layanan->nama_layanan }}</h5>
                <form action="/admin-area/layanan/edit/update" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id_layanan" value="{{ $layanan->id_layanan }}">
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-8">
                                <label for="nama_layanan" class="form-label">Nama Layanan <span class="text-danger">*</span></label>
                                <input type="text" id="nama_layanan" name="nama_layanan" class="form-control"
                                    required value="{{ old('nama_layanan', $layanan->nama_layanan) }}">
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="ikon" class="form-label">Ikon BoxIcons</label>
                                <input type="text" id="ikon" name="ikon" class="form-control"
                                    value="{{ old('ikon', $layanan->ikon) }}">
                                <small class="text-muted">Cari di <a href="https://boxicons.com" target="_blank">boxicons.com</a></small>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="harga_mulai" class="form-label">Harga Mulai (Rp)</label>
                                <input type="number" id="harga_mulai" name="harga_mulai" class="form-control"
                                    value="{{ old('harga_mulai', $layanan->harga_mulai) }}">
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="harga_sampai" class="form-label">Harga Sampai (Rp)</label>
                                <input type="number" id="harga_sampai" name="harga_sampai" class="form-control"
                                    value="{{ old('harga_sampai', $layanan->harga_sampai) }}">
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="durasi" class="form-label">Estimasi Durasi</label>
                                <input type="text" id="durasi" name="durasi" class="form-control"
                                    value="{{ old('durasi', $layanan->durasi) }}">
                            </div>
                            <div class="mb-3 col-md-8">
                                <label for="foto" class="form-label">Ganti Foto (Opsional)</label>
                                @if($layanan->images)
                                <div class="mb-2">
                                    <img src="{{ asset('img/layanan/'.$layanan->images) }}" height="60" class="rounded" alt="Foto saat ini">
                                    <small class="text-muted d-block">Foto saat ini. Upload baru untuk mengganti.</small>
                                </div>
                                @endif
                                <input type="file" id="foto" name="foto" class="form-control" accept="image/png, image/jpeg, image/webp">
                                <small class="text-muted">Format: JPG, PNG, WEBP. Maks 2MB.</small>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="urutan" class="form-label">Urutan Tampil</label>
                                <input type="number" id="urutan" name="urutan" class="form-control"
                                    value="{{ old('urutan', $layanan->urutan) }}" min="0">
                            </div>
                            <div class="mb-3 col-md-12">
                                <label for="deskripsi" class="form-label">Deskripsi Layanan</label>
                                <textarea name="deskripsi" id="deskripsi" class="form-control"
                                    rows="4">{{ old('deskripsi', $layanan->deskripsi) }}</textarea>
                            </div>
                            <div class="mb-3 col-md-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="aktif" name="aktif"
                                        {{ $layanan->aktif ? 'checked' : '' }}>
                                    <label class="form-check-label" for="aktif">
                                        Layanan Aktif (tampil di halaman publik)
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bx bx-save me-1"></i> Simpan Perubahan
                            </button>
                            <a href="/admin-area/layanan" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('admin.layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">
            <a href="/admin-area" class="a-breadcrumbs">Beranda</a> /
            <a href="/admin-area/layanan" class="a-breadcrumbs">Layanan</a> /
        </span> Layanan Baru
    </h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Tambah Layanan Klinik Baru</h5>
                <form action="/admin-area/layanan/submit" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-8">
                                <label for="nama_layanan" class="form-label">Nama Layanan <span class="text-danger">*</span></label>
                                <input type="text" id="nama_layanan" name="nama_layanan" class="form-control"
                                    required placeholder="Contoh: Scaling / Pembersihan Karang Gigi"
                                    value="{{ old('nama_layanan') }}">
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="ikon" class="form-label">Ikon BoxIcons</label>
                                <input type="text" id="ikon" name="ikon" class="form-control"
                                    placeholder="bx-plus-medical" value="{{ old('ikon', 'bx-plus-medical') }}">
                                <small class="text-muted">Cari di <a href="https://boxicons.com" target="_blank">boxicons.com</a></small>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="harga_mulai" class="form-label">Harga Mulai (Rp)</label>
                                <input type="number" id="harga_mulai" name="harga_mulai" class="form-control"
                                    placeholder="100000" value="{{ old('harga_mulai') }}">
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="harga_sampai" class="form-label">Harga Sampai (Rp)</label>
                                <input type="number" id="harga_sampai" name="harga_sampai" class="form-control"
                                    placeholder="500000" value="{{ old('harga_sampai') }}">
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="durasi" class="form-label">Estimasi Durasi</label>
                                <input type="text" id="durasi" name="durasi" class="form-control"
                                    placeholder="30 - 60 menit" value="{{ old('durasi') }}">
                            </div>
                            <div class="mb-3 col-md-8">
                                <label for="foto" class="form-label">Foto Layanan (Opsional)</label>
                                <input type="file" id="foto" name="foto" class="form-control"
                                    accept="image/png, image/jpeg, image/webp">
                                <small class="text-muted">Format: JPG, PNG, WEBP. Maks 2MB.</small>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="urutan" class="form-label">Urutan Tampil</label>
                                <input type="number" id="urutan" name="urutan" class="form-control"
                                    placeholder="0" value="{{ old('urutan', 0) }}" min="0">
                                <small class="text-muted">Angka kecil tampil lebih dulu.</small>
                            </div>
                            <div class="mb-3 col-md-12">
                                <label for="deskripsi" class="form-label">Deskripsi Layanan</label>
                                <textarea name="deskripsi" id="deskripsi" class="form-control" rows="4"
                                    placeholder="Jelaskan prosedur, manfaat, dan informasi penting lainnya...">{{ old('deskripsi') }}</textarea>
                            </div>
                            <div class="mb-3 col-md-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="aktif" name="aktif" checked>
                                    <label class="form-check-label" for="aktif">
                                        Layanan Aktif (tampil di halaman publik)
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bx bx-save me-1"></i> Simpan Layanan
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

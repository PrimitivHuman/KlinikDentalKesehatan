@extends('admin.layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><a href="/admin-area"
                class="a-breadcrumbs">Beranda</a> /</span> Informasi Umum</h4>
    @include('admin.layout.alert')
    <div class="card mb-4">
        <h5 class="card-header">
            Tentang
        </h5>
        <div class="row p-4">
            <div class="col-sm-12 col-md-4 mb-4">
                <div class="row">
                    <div class="col-6">
                        <label class="form-label">Foto</label>
                    </div>
                    <div class="col-6">
                        <div class="d-flex flex-row-reverse">
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalFoto"><i class='bx bx-edit-alt'></i></button>
                        </div>
                    </div>
                    <div class="col-12 pt-2">
                        <img src="{{ asset('../main/img/logo/'.$about[0] -> foto_sampul) }}" alt="Gambar sampul" class="img-fluid rounded">
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-8">
                <div class="row">
                    <div class="col-6">
                        <label class="form-label">Deskripsi</label>
                    </div>
                    <div class="col-6">
                        <div class="d-flex flex-row-reverse">
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalDeskripsi"><i class='bx bx-edit-alt'></i></button>
                        </div>
                    </div>
                </div>
                {!! \App\Support\Sanitizer::html($about[0] -> informasi_umum) !!}
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <h4 class="card-header">
            Visi Misi
        </h4>
        <div class="row p-4">
            <div class="col-sm-12 col-md-6">
                <div class="row">
                    <div class="col-6">
                        <label class="form-label">Visi</label>
                    </div>
                    <div class="col-6">
                        <div class="d-flex flex-row-reverse">
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalVisi"><i class='bx bx-edit-alt'></i></button>
                        </div>
                    </div>
                </div>
                {!! \App\Support\Sanitizer::html($about[0] -> visi) !!}
            </div>
            <div class="col-sm-12 col-md-6">
                <div class="row">
                    <div class="col-6">
                        <label class="form-label">Misi</label>
                    </div>
                    <div class="col-6">
                        <div class="d-flex flex-row-reverse">
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalMisi"><i class='bx bx-edit-alt'></i></button>
                        </div>
                    </div>
                </div>
                {!! \App\Support\Sanitizer::html($about[0] -> misi) !!}
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <h4 class="card-header">
            Tugas Pokok &amp; Fungsi
        </h4>
        <div class="row p-4">
            <div class="col-12">
                <div class="d-flex flex-row-reverse mb-2">
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTupoksi"><i class='bx bx-edit-alt'></i></button>
                </div>
                {!! \App\Support\Sanitizer::html($about[0] -> tupoksi) !!}
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalFoto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form action="/admin-area/informasi-umum/edit-foto" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h5 class="modal-title">Edit Foto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 col-lg-6">
                            <label class="form-label">Foto</label>
                            <input name="foto_old" type="text" hidden readonly value="{{ $about[0] -> foto_sampul }}" />
                            <img src="{{ '../main/img/logo/'.$about[0] -> foto_sampul }}" alt="preview image" class="d-block rounded img-fluid" width="400" id="uploadedAvatar" />
                        </div>
                        <div class="col-sm-12 col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Opsi Unggah</label>
                                <div class="button-wrapper">
                                    <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                        <span class="d-none d-sm-block">Pilih Foto</span>
                                        <i class="bx bx-upload d-block d-sm-none"></i>
                                        <input name="foto" type="file" id="upload" class="account-file-input" hidden
                                            accept="image/png, image/jpeg" />
                                    </label>
                                    <button type="button"
                                        class="btn btn-outline-secondary account-image-reset mb-4">
                                        <i class="bx bx-reset d-block d-sm-none"></i>
                                        <span class="d-none d-sm-block">Reset</span>
                                    </button>
                                    <p class="text-muted mb-0">Tipe file : .jpg atau .png. Ukuran maks 800KB</p>
                                    @error('foto_sampul')
                                    <div id="defaultFormControlHelp" class="form-text bg-warning text-black">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modalDeskripsi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form action="/admin-area/informasi-umum/edit-deskripsi" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h5 class="modal-title">Edit Deskripsi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="informasi_umum" id="summernote">{!! \App\Support\Sanitizer::html($about[0] -> informasi_umum) !!}</textarea>
                                @error('informasi_umum')
                                <div id="defaultFormControlHelp" class="form-text bg-warning text-black">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modalVisi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form action="/admin-area/informasi-umum/edit-visi" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h5 class="modal-title">Edit Visi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Visi</label>
                                <textarea name="visi" id="summernote2">{!! \App\Support\Sanitizer::html($about[0] -> visi) !!}</textarea>
                                @error('visi')
                                <div id="defaultFormControlHelp" class="form-text bg-warning text-black">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modalMisi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form action="/admin-area/informasi-umum/edit-misi" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h5 class="modal-title">Edit Misi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Misi</label>
                                <textarea name="misi" id="summernote3">{!! \App\Support\Sanitizer::html($about[0] -> misi) !!}</textarea>
                                @error('misi')
                                <div id="defaultFormControlHelp" class="form-text bg-warning text-black">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modalTupoksi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form action="/admin-area/informasi-umum/edit-tupoksi" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h5 class="modal-title">Edit Tugas Pokok & Fungsi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Tugas Pokok & Fungsi</label>
                                <textarea name="tupoksi" id="summernote4">{!! \App\Support\Sanitizer::html($about[0] -> tupoksi) !!}</textarea>
                                @error('tupoksi')
                                <div id="defaultFormControlHelp" class="form-text bg-warning text-black">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
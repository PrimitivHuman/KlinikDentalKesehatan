@extends('admin.layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><a href="/admin-area"
                class="a-breadcrumbs">Beranda</a> / <a href="/admin-area/akun" class="a-breadcrumbs">Data Akun</a> / </span>Edit</h4>
    <div class="card">
        <h5 class="card-header">
            Edit Pengguna
        </h5>
        <form action="/admin-area/akun/edit/update" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div class="mb-3 row">
                            <label class="col-md-3 col-form-label">ID Akun</label>
                            <div class="col-md-9">
                                <input class="form-control" type="text" name="id" required readonly value="{{ $account[0] -> id }}"/>
                                @error('id')
                                <div id="defaultFormControlHelp" class="form-text">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div class="mb-3 row">
                            <label class="col-md-3 col-form-label">Nama Pengguna</label>
                            <div class="col-md-9">
                                <input class="form-control" type="text" name="name" required value="{{ old('name' ,$account[0] -> name) }}"/>
                                @error('name')
                                <div id="defaultFormControlHelp" class="form-text">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div class="mb-3 row">
                            <label class="col-md-3 col-form-label">Username</label>
                            <div class="col-md-9">
                                <input class="form-control" type="email" name="email" required value="{{ old('email' ,$account[0] -> email) }}"/>
                                @error('email')
                                <div id="defaultFormControlHelp" class="form-text">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div class="mb-3 row">
                            <label class="col-md-3 col-form-label">Password</label>
                            <div class="col-md-9">
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalGantiSandi">Ganti kata sandi</button>
                                {{-- <input class="form-control" type="password" name="password" value="{{ old('password' ,$account[0] -> password) }}"/> --}}
                                @error('password')
                                <div id="defaultFormControlHelp" class="form-text">
                                    {{ $message }}
                                </div>
                                @enderror
                                @if (Session::has('error_pass'))
                                    <div id="defaultFormControlHelp" class="form-text">
                                        {{ Session::get('error_pass') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div class="mb-3 row">
                            <label class="col-md-3 col-form-label">Foto</label>
                            <div class="col-sm-12 col-xl-6">
                                <img src="{{ asset('../img/account/'.$account[0] -> profile_pict) }}" alt="Pratinjau Gambar..." class="d-block rounded img-fluid mb-3" id="uploadedAvatar" style="max-height: 300px;" />
                                <div class="button-wrapper">
                                    <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                        <span class="d-none d-sm-block">Pilih Foto</span>
                                        <i class="bx bx-upload d-block d-sm-none"></i>
                                        <input name="foto" type="file" id="upload"
                                            class="account-file-input" hidden
                                            accept="image/png, image/jpeg"/>
                                    </label>
                                    <button type="button"
                                        class="btn btn-outline-secondary account-image-reset mb-4">
                                        <i class="bx bx-reset d-block d-sm-none"></i>
                                        <span class="d-none d-sm-block">Reset</span>
                                    </button>
                                    <p class="text-muted mb-0">Tipe file : .jpg atau .png. Ukuran maks
                                        800KB</p>
                                    @error('profile_pict')
                                    <div id="defaultFormControlHelp" class="form-text">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class=" card-body">
                <div class="row">
                    <div class="col-lg-6 col-sm-6 col-md-6">
                        <div class="mb-3">
                            <div class="text-start">
                                <button class="btn btn-primary" type="submit">
                                    <span class="align-middle">Simpan</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-md-6">
                        <div class="text-end">
                            @if ($admin === true)
                            <a class="btn btn-warning" href="/admin-area/akun/detail">
                                <span class="align-middle">Kembali</span>
                            </a>
                            @else
                            <a class="btn btn-warning" href="/admin-area/akun">
                                <span class="align-middle">Kembali</span>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="modalGantiSandi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form action="/admin-area/akun/edit/update" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h5 class="modal-title">Edit Sandi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" readonly name="id" value="{{ old('id' ,$account[0] -> id) }}">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Masukkan sandi lama</label>
                                <input type="password" class="form-control" name="old_password" placeholder="Masukkan kata sandi lama">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Masukkan sandi baru</label>
                                <input type="password" class="form-control" name="password" placeholder="Minimal 8 karakter">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Konfirmasi sandi baru</label>
                                <input type="password" class="form-control" name="retype_password" placeholder="Ketik ulang kata sandi baru">
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
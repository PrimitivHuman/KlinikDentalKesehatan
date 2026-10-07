@extends('admin.layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin /</span> Pengaturan Profil</h4>

    @include('admin.layout.alert')

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Detail Profil Admin</h5>
                <form action="/admin-area/pengaturan/update" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="d-flex align-items-start align-items-sm-center gap-4 mb-4">
                            <img src="{{ asset('img/account/'.$user->profile_pict) }}" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
                            <div class="button-wrapper">
                                <label for="upload" class="btn btn-primary me-2 mb-2" tabindex="0">
                                    <span class="d-none d-sm-block">Unggah Foto Baru</span>
                                    <i class="bx bx-upload d-block d-sm-none"></i>
                                    <input type="file" id="upload" name="foto" class="account-file-input" hidden accept="image/png, image/jpeg, image/webp" />
                                </label>
                                <p class="text-muted mb-0">Format: JPG, PNG, WEBP. Ukuran maksimal 2MB.</p>
                            </div>
                        </div>

                        <hr class="my-4" />

                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input class="form-field form-control" type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="email" class="form-label">Alamat E-Mail</label>
                                <input class="form-field form-control" type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required />
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="old_password" class="form-label">Kata Sandi Lama (Opsional)</label>
                                <input class="form-field form-control" type="password" id="old_password" name="old_password" placeholder="Masukkan kata sandi lama" />
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="new_password" class="form-label">Kata Sandi Baru</label>
                                <input class="form-field form-control" type="password" id="new_password" name="new_password" placeholder="Minimal 8 karakter" />
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="confirm_password" class="form-label">Konfirmasi Kata Sandi Baru</label>
                                <input class="form-field form-control" type="password" id="confirm_password" name="confirm_password" placeholder="Ulangi kata sandi baru" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary me-2">Simpan Perubahan</button>
                            <button type="reset" class="btn btn-outline-secondary">Batal</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

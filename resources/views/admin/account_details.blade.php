@extends('admin.layout.main')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Data Pengguna /</span> Detail Pengguna</h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Profile Details</h5>    
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-6 col-lg-4">
                            <label for="firstName" class="form-label">Nama Pengguna</label>
                            <input class="form-control" type="text" value="{{ Auth::user()->name }}" readonly>
                        </div>
                        <div class="mb-3 col-md-6 col-lg-4">
                            <label for="lastName" class="form-label">Username</label>
                            <input class="form-control" type="email" value="{{ Auth::user()->email }}" readonly>
                        </div>
                        <div class="mb-3 col-md-6 col-lg-4">
                            <label for="lastName" class="form-label">Tanggal Registrasi</label>
                            <input class="form-control" type="email" value="{{ Auth::user()->created_at }}" readonly>
                        </div>
                        {{-- <div class="form-password-toggle mb-3 col-md-6 col-lg-4">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="basic-default-password12"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="basic-default-password2" value="{{ Auth::user()->password }}" readonly />
                                <span id="basic-default-password2" class="input-group-text cursor-pointer"><i
                                        class="bx bx-hide"></i></span>
                            </div>
                        </div> --}}
                        <div class="mb-3 col-md-6 col-lg-4">
                            <label for="pro_pict" class="form-label">Foto Profil</label>
                            <img src="{{ asset('img/account/'.Auth::user()->profile_pict) }}" alt="user-avatar"
                            class="d-block rounded img-fluid" height="200" width="200" id="uploadedAvatar" />
                        </div>
                    </div>
                    <div class="mt-2 col-lg-2 col-md-2">
                        <label for="edit" class="form-label">Aksi</label>
                        <a href="/admin-area/akun/edit/{{ encrypt(Auth::user()->id) }}/{{ true }}" class="btn btn-primary me-2 form-control">Edit Akun</a>
                    </div>
                </div>
                <!-- /Account -->
            </div>
            <div class="card">
                <h5 class="card-header">Hapus Akun</h5>
                <div class="card-body">
                    <div class="mb-3 col-12 mb-0">
                        <div class="alert alert-warning">
                            <h6 class="alert-heading fw-bold mb-1">Apakan anda yakin ingin menghapus akun?</h6>
                            <p class="mb-0">Aksi ini tidak dapat dibatalkan, tolong gunakan fitur ini dengan baik.</p>
                        </div>
                    </div>
                    <form id="formAccountDeactivation" action="/admin-area/akun/delete/{{ Crypt::encrypt(Auth::user()->id) }}/1" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="accountActivation" required>
                            <label class="form-check-label" for="accountActivation">Saya ingin menghapus akun
                                saya.</label>
                        </div>
                        <button type="submit" class="btn btn-danger deactivate-account">Hapus Akun</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
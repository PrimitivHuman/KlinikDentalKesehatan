@extends('admin.layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><a href="/admin-area"
                class="a-breadcrumbs">Beranda</a> /</span> Data Galeri</h4>
    @include('admin.layout.alert')
    <!-- Examples -->
    <div class="row">
        <div class="col-md-6">
            <a href="/admin-area/galeri/new" class="btn btn-primary btn-sm pl-4">Data Baru</a>
        </div>
        <div class="col-md-6">
            <div class="d-flex flex-row-reverse">
                <div class="row mb-4">
                    <div class="col-auto">
                        <label for="cari" class="col-form-label">Cari Foto</label>
                      </div>
                      <div class="col-auto">
                        <form action="/admin-area/galeri" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="input-group">
                            <input required type="text" id="cari" class="form-control" name="cari" placeholder="Masukkan keyword...">
                            <button class="btn btn-outline-primary" type="submit">Cari</button>
                        </div>
                        @error('judul')
                        <div class="form-text">
                            <i class="ri-error-warning-line"></i>
                            Masukkan keyword pencarian yang valid.
                        </div>
                        @enderror
                        </form>
                      </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-5">
        @if (count($gallery) === 0 || Session::has('message'))
        <div class="card mb-4 text-center">
            <h5 class="card-header">Data Tidak Ditemukan!</h5>
        </div>
        @else
        @foreach ($gallery as $data)
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card h-100">
                <img class="card-img-top" src="{{ asset('img/gallery/'.$data -> images) }}" alt="Card image cap" />
                <div class="card-body">
                    <h5 class="card-title">{{ $data -> id_galeri}}</h5>
                    <p class="card-text">
                        {!! html_entity_decode($data -> judul) !!}
                    </p>
                    <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                        <a href="/admin-area/galeri/edit/{{ Crypt::encrypt($data->id_galeri) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bx bx-edit-alt"></i> Edit
                        </a>
                        <a href="/admin-area/galeri/delete/{{ Crypt::encrypt($data->id_galeri) }}" onclick="return confirm('Hapus foto {{ $data->id_galeri }}?')" class="btn btn-sm btn-outline-danger">
                            <i class="bx bx-trash"></i> Hapus
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        {{ $gallery->links('admin.layout.pagination') }}
        @endif
    </div>
    <!-- Examples -->
</div>
@endsection
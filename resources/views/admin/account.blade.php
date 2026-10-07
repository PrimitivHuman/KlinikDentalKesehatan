@extends('admin.layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Breadcrumb -->
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light"><a href="/admin-area" class="a-breadcrumbs">Beranda</a> /</span> Data Akun
    </h4>

    @include('admin.layout.alert')

    <div class="card border-0 shadow-sm" style="border-radius: 14px;">
        <!-- Card Header: Action Button Left & Search Bar Right -->
        <div class="card-header bg-white border-bottom py-3 px-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-3">
                <!-- Action Button: Tambah Akun Baru -->
                <div class="d-flex align-items-center gap-2">
                    <a href="/admin-area/akun/new" class="btn btn-primary d-inline-flex align-items-center gap-2 shadow-sm" style="border-radius: 8px; padding: 8px 18px; font-weight: 600;">
                        <i class="bx bx-user-plus fs-5"></i>
                        <span>Tambah Akun Baru</span>
                    </a>
                </div>

                <!-- Form Pencarian Akun -->
                <form action="/admin-area/akun" method="GET" class="d-flex align-items-center gap-2 m-0">
                    <div class="input-group-search" style="min-width: 250px; height: 38px;">
                        <span class="search-icon">
                            <i class="bx bx-search"></i>
                        </span>
                        <input type="text" id="cari" name="cari"
                               class="form-control"
                               placeholder="Cari nama, email, ID..."
                               value="{{ request('cari') }}"
                               autocomplete="off">
                    </div>
                    <button class="btn btn-outline-primary btn-sm px-3 d-inline-flex align-items-center gap-1"
                            type="submit"
                            style="border-radius: 8px; height: 38px;">
                        <i class="bx bx-filter-alt"></i>
                        <span>Cari</span>
                    </button>
                    @if(request('cari') || Session::has('message'))
                        <a href="/admin-area/akun" class="btn btn-outline-secondary btn-sm px-2 d-inline-flex align-items-center" style="border-radius: 8px; height: 38px;" title="Reset Pencarian">
                            <i class="bx bx-x fs-5"></i>
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Tabel Data Akun -->
        <div class="table-responsive text-nowrap" style="min-height: 240px;">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="text-nowrap">
                        <th class="ps-4" style="width: 60px;">No</th>
                        <th>Foto Profil</th>
                        <th>ID Akun</th>
                        <th>Nama Pengguna</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th class="text-end pe-4" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($account) === 0 || Session::has('message'))
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bx bx-user-x display-6 d-block mb-2 text-warning opacity-75"></i>
                            <strong>Tidak Ada Data Akun!</strong>
                            <div class="small mt-1">{{ Session::get('message') ?? 'Belum ada akun admin yang sesuai kriteria.' }}</div>
                        </td>
                    </tr>
                    @else
                    @foreach ($account as $data)
                    <tr>
                        <th scope="row" class="ps-4 text-muted small">{{ $loop->iteration }}</th>
                        <td>
                            @if($data->profile_pict && file_exists(public_path('img/account/'.$data->profile_pict)))
                                <img src="{{ asset('img/account/'.$data->profile_pict) }}" alt="{{ $data->name }}"
                                     class="rounded-circle shadow-sm border"
                                     style="width: 44px; height: 44px; object-fit: cover;" />
                            @else
                                <div class="rounded-circle bg-label-primary d-inline-flex align-items-center justify-content-center text-primary fw-bold shadow-sm border"
                                     style="width: 44px; height: 44px; font-size: 16px;">
                                    {{ strtoupper(substr($data->name, 0, 1)) }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-light text-dark font-monospace border">{{ $data->id }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <strong class="text-dark">{{ $data->name }}</strong>
                                @if(Auth::id() === $data->id)
                                    <span class="badge bg-label-info" style="font-size: 10px;">Akun Anda</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="text-muted">{{ $data->email }}</span>
                        </td>
                        <td>
                            <span class="badge bg-label-danger px-2 py-1">Superadmin</span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-inline-flex gap-1">
                                <a class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1"
                                   href="/admin-area/akun/edit/{{ Crypt::encrypt($data->id) }}/0"
                                   title="Edit Akun">
                                    <i class="bx bx-edit-alt"></i>
                                    <span>Edit</span>
                                </a>
                                @if(Auth::id() !== $data->id)
                                <form action="/admin-area/akun/delete/{{ Crypt::encrypt($data->id) }}/0" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ addslashes($data->name) }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center" title="Hapus Akun">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <div class="p-3 border-top d-flex justify-content-center">
            {{ $account->links('admin.layout.pagination') }}
        </div>
    </div>
</div>
@endsection
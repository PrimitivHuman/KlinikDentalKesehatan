@extends('admin.layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><a href="/admin-area" class="a-breadcrumbs">Beranda</a> /</span> Data Pasien Janji Temu</h4>
    
    @include('admin.layout.alert')

    <div class="card border-0 shadow-sm" style="border-radius: 14px;">
        <div class="card-header bg-white border-bottom py-3 px-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-3">
                <!-- Action Button Left: Export Excel -->
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('Pasien.export') }}" class="btn btn-outline-success btn-sm d-inline-flex align-items-center gap-1 shadow-none" style="border-radius: 8px; padding: 7px 14px;">
                        <i class="bx bx-download fs-6"></i>
                        <span>Export Excel</span>
                    </a>
                </div>

                <!-- Pencarian + Filter (status, dokter, rentang tanggal) -->
                <form action="/admin-area/pasien" method="GET" class="d-flex flex-wrap align-items-center gap-2 m-0">
                    <div class="input-group-search" style="min-width: 230px; height: 38px;">
                        <span class="search-icon">
                            <i class="bx bx-search"></i>
                        </span>
                        <input type="text" id="cari" name="cari"
                               class="form-control"
                               placeholder="Cari nama, ID, no HP..."
                               value="{{ request('cari') }}"
                               autocomplete="off">
                    </div>
                    <select name="status" class="form-select form-select-sm" style="width: 150px; border-radius: 8px; height: 38px;" aria-label="Filter status">
                        <option value="">Semua Status</option>
                        @foreach (['pending' => 'Pending', 'confirmed' => 'Dikonfirmasi', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'] as $val => $lbl)
                            <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                        @endforeach
                    </select>
                    <select name="dokter" class="form-select form-select-sm" style="width: 170px; border-radius: 8px; height: 38px;" aria-label="Filter dokter">
                        <option value="">Semua Dokter</option>
                        @foreach (($dokters ?? []) as $d)
                            <option value="{{ $d->id_dokter }}" {{ request('dokter') === $d->id_dokter ? 'selected' : '' }}>{{ $d->nama_dokter }}</option>
                        @endforeach
                    </select>
                    <!-- Filter Rentang Tanggal (Dari s/d Sampai) -->
                    <div class="input-group input-group-sm" style="width: auto;">
                        <span class="input-group-text bg-light text-muted px-2" style="font-size: 12px; font-weight: 600; border-radius: 8px 0 0 8px;">Dari</span>
                        <input type="date" name="dari" value="{{ request('dari') }}" class="form-control form-control-sm" style="width: 135px; height: 38px; font-size: 12px;" title="Dari tanggal" aria-label="Dari tanggal">
                        <span class="input-group-text bg-light text-muted px-2" style="font-size: 12px; font-weight: 600;">s/d</span>
                        <input type="date" name="sampai" value="{{ request('sampai') }}" class="form-control form-control-sm" style="width: 135px; height: 38px; font-size: 12px; border-radius: 0 8px 8px 0;" title="Sampai tanggal" aria-label="Sampai tanggal">
                    </div>
                    <button class="btn btn-outline-primary btn-sm px-3 d-inline-flex align-items-center gap-1"
                            type="submit"
                            style="border-radius: 8px; height: 38px;">
                        <i class="bx bx-filter-alt"></i>
                        <span>Terapkan</span>
                    </button>
                    @if(request()->hasAny(['cari', 'status', 'dokter', 'dari', 'sampai']) || Session::has('message'))
                        <a href="/admin-area/pasien" class="btn btn-outline-secondary btn-sm px-2 d-inline-flex align-items-center" style="border-radius: 8px; height: 38px;" title="Reset">
                            <i class="bx bx-x fs-5"></i>
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <div class="table-responsive text-nowrap" style="min-height: 320px;">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="text-nowrap">
                        <th class="ps-4">No</th>
                        <th>ID / Nama Pasien</th>
                        <th>Tanggal Janji</th>
                        <th>Dokter Pilihan</th>
                        <th>No. HP</th>
                        <th>Keluhan & Tindakan</th>
                        <th>Total Biaya</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($pasien) === 0 || Session::has('message'))
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="bx bx-user-x display-6 d-block mb-2 text-warning opacity-75"></i>
                            <strong>Tidak Ada Data Pasien!</strong>
                            <div class="small mt-1">{{ Session::get('message') ?? 'Belum ada pendaftaran janji temu pasien.' }}</div>
                        </td>
                    </tr>
                    @else
                    @foreach ($pasien as $data)
                    @php
                        $cleanHp = preg_replace('/[^0-9]/', '', $data->no_hp_pasien);
                        if (str_starts_with($cleanHp, '0')) {
                            $cleanHp = '62' . substr($cleanHp, 1);
                        }
                        $waMsg = rawurlencode("Halo {$data->nama_pasien},\nKami dari Klinik FAM Dental Care ingin mengonfirmasi jadwal janji temu Anda:\n- Tanggal: {$data->tanggal_janji}\n- Dokter: ".($data->dokter_pilihan ?? 'Klinik')."\n\nMohon konfirmasi kedatangan Anda. Terima kasih!");
                    @endphp
                    <tr>
                        <th scope="row" class="ps-4 text-muted small">{{ $loop->iteration }}</th> 
                        <td>
                            <strong class="text-dark">{{ $data->nama_pasien }}</strong><br>
                            <small class="text-muted font-monospace">{{ $data->formatted_id }}</small>
                            <small class="text-muted"> | {{ $data->email_pasien }}</small>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($data->tanggal_janji)->format('d M Y') }}</div>
                            <small class="text-muted"><i class="bx bx-time-five"></i> {{ \Carbon\Carbon::parse($data->tanggal_janji)->format('H:i') }} WIB</small>
                        </td>
                        <td>
                            <span class="badge bg-light text-primary border fw-normal">{{ $data->dokter_pilihan ?? 'Dokter Umum/Jaga' }}</span>
                        </td>
                        <td>
                            <a href="https://wa.me/{{ $cleanHp }}?text={{ $waMsg }}" target="_blank" class="btn btn-xs btn-outline-success d-inline-flex align-items-center gap-1" title="Chat WhatsApp">
                                <i class="bx bxl-whatsapp fs-6"></i> <span>{{ $data->no_hp_pasien }}</span>
                            </a>
                        </td>
                        <td>
                            <small class="d-block"><strong>Keluhan:</strong> {{ Str::limit($data->keluhan_pasien, 30) }}</small>
                            <small class="d-block text-primary"><strong>Tindakan:</strong> {{ $data->tindakan_pasien ?? '-' }}</small>
                        </td>
                        <td class="fw-bold text-dark">
                            {{ $data->total_harga_pasien ? 'Rp '.number_format((float) str_replace(['Rp', '.', ' '], '', $data->total_harga_pasien), 0, ',', '.') : '-' }}
                        </td>
                        <td>
                            <!-- Status Dropdown: menggunakan Popper strategy fixed agar tidak terpotong container -->
                            <div class="dropdown">
                                <button type="button" class="btn btn-sm btn-status-dropdown dropdown-toggle shadow-none
                                    @if($data->status === 'completed') btn-success
                                    @elseif($data->status === 'confirmed') btn-info text-white
                                    @elseif($data->status === 'cancelled') btn-danger
                                    @else btn-warning text-dark @endif" 
                                    data-bs-toggle="dropdown" 
                                    aria-expanded="false">
                                    <span>{{ ucfirst($data->status ?? 'pending') }}</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0 py-2" style="border-radius: 12px; min-width: 175px; z-index: 1060;">
                                    <li class="px-3 py-1 text-muted small fw-semibold text-uppercase" style="font-size: 10px; letter-spacing: 0.5px;">Ubah Status:</li>
                                    <li>
                                        <form action="/admin-area/pasien/status/{{ Crypt::encrypt($data->id_pasien) }}/pending" method="POST" class="m-0 p-0">
                                            @csrf
                                            <button type="submit" class="dropdown-item d-flex align-items-center gap-2 py-2 {{ $data->status === 'pending' ? 'active bg-light text-dark fw-bold' : '' }}" style="border:none; background:transparent; width:100%; text-align:left;">
                                                <span class="badge bg-warning text-dark px-2 py-1" style="font-size: 10px;">Pending</span>
                                                <span class="small">Menunggu</span>
                                                @if($data->status === 'pending')<i class="bx bx-check ms-auto text-primary"></i>@endif
                                            </button>
                                        </form>
                                    </li>
                                    <li>
                                        <form action="/admin-area/pasien/status/{{ Crypt::encrypt($data->id_pasien) }}/confirmed" method="POST" class="m-0 p-0">
                                            @csrf
                                            <button type="submit" class="dropdown-item d-flex align-items-center gap-2 py-2 {{ $data->status === 'confirmed' ? 'active bg-light text-dark fw-bold' : '' }}" style="border:none; background:transparent; width:100%; text-align:left;">
                                                <span class="badge bg-info px-2 py-1" style="font-size: 10px;">Confirmed</span>
                                                <span class="small">Konfirmasi</span>
                                                @if($data->status === 'confirmed')<i class="bx bx-check ms-auto text-primary"></i>@endif
                                            </button>
                                        </form>
                                    </li>
                                    <li>
                                        <form action="/admin-area/pasien/status/{{ Crypt::encrypt($data->id_pasien) }}/completed" method="POST" class="m-0 p-0">
                                            @csrf
                                            <button type="submit" class="dropdown-item d-flex align-items-center gap-2 py-2 {{ $data->status === 'completed' ? 'active bg-light text-dark fw-bold' : '' }}" style="border:none; background:transparent; width:100%; text-align:left;">
                                                <span class="badge bg-success px-2 py-1" style="font-size: 10px;">Completed</span>
                                                <span class="small">Selesai</span>
                                                @if($data->status === 'completed')<i class="bx bx-check ms-auto text-primary"></i>@endif
                                            </button>
                                        </form>
                                    </li>
                                    <li>
                                        <form action="/admin-area/pasien/status/{{ Crypt::encrypt($data->id_pasien) }}/cancelled" method="POST" class="m-0 p-0">
                                            @csrf
                                            <button type="submit" class="dropdown-item d-flex align-items-center gap-2 py-2 {{ $data->status === 'cancelled' ? 'active bg-light text-dark fw-bold' : '' }}" style="border:none; background:transparent; width:100%; text-align:left;">
                                                <span class="badge bg-danger px-2 py-1" style="font-size: 10px;">Cancelled</span>
                                                <span class="small">Batal</span>
                                                @if($data->status === 'cancelled')<i class="bx bx-check ms-auto text-primary"></i>@endif
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-inline-flex gap-1">
                                <a class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1" href="/admin-area/pasien/edit/{{ Crypt::encrypt($data->id_pasien) }}">
                                    <i class="bx bx-edit-alt"></i> <span>Edit</span>
                                </a>
                                <a class="btn btn-sm btn-outline-info d-inline-flex align-items-center gap-1" href="/admin-area/pasien/invoice/{{ Crypt::encrypt($data->id_pasien) }}" target="_blank">
                                    <i class="bx bx-receipt"></i> <span>Invoice</span>
                                </a>
                                @php
                                    $cleanPhone = preg_replace('/\D/', '', $data->no_hp_pasien ?? '');
                                    if (str_starts_with($cleanPhone, '0')) {
                                        $cleanPhone = '62' . substr($cleanPhone, 1);
                                    }
                                @endphp
                                @if(!empty($cleanPhone))
                                <a class="btn btn-sm btn-outline-success d-inline-flex align-items-center" 
                                   href="https://wa.me/{{ $cleanPhone }}?text=Halo%20Bpk%2FIbu%20{{ urlencode($data->nama_pasien) }}%2C%20kami%20dari%20Klinik%20FAM%20Dental%20Care%20ingin%20mengonfirmasi%20jadwal%20janji%20temu%20Anda." 
                                   target="_blank" title="Kirim Pengingat WhatsApp">
                                    <i class="bx bxl-whatsapp"></i>
                                </a>
                                @endif
                                <form action="/admin-area/pasien/delete/{{ Crypt::encrypt($data->id_pasien) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data pasien {{ addslashes($data->nama_pasien) }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center" title="Hapus">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        
        <div class="p-3 border-top d-flex justify-content-center">
            {{ $pasien->links('admin.layout.pagination') }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof bootstrap !== 'undefined' && bootstrap.Dropdown) {
            document.querySelectorAll('.btn-status-dropdown').forEach(function (btn) {
                new bootstrap.Dropdown(btn, {
                    popperConfig: function (defaultBsPopperConfig) {
                        return Object.assign({}, defaultBsPopperConfig, { strategy: 'fixed' });
                    }
                });
            });
        }
    });
</script>
@endpush
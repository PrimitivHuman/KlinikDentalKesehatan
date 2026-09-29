@extends('admin.layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><a href="/admin-area" class="a-breadcrumbs">Beranda</a> /</span> Data Pasien Janji Temu</h4>
    
    @include('admin.layout.alert')

    <div class="card">
        <h5 class="card-header">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <a href="{{ route('Pasien.export') }}" class="btn btn-outline-success btn-sm">
                        <i class="bx bx-download me-1"></i> Export Excel
                    </a>
                </div>
                <div class="col-md-8">
                    <div class="d-flex flex-row-reverse">
                        <form action="/admin-area/pasien" method="POST">
                            @csrf
                            <div class="input-group">
                                <input required type="text" id="cari" class="form-control" name="cari" placeholder="Cari nama, ID, no HP...">
                                <button class="btn btn-outline-primary" type="submit"><i class="bx bx-search"></i> Cari</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </h5>

        <div class="table-responsive text-nowrap">
            <table class="table table-hover align-middle">
                <thead>
                    <tr class="text-nowrap">
                        <th>No</th>
                        <th>ID / Nama Pasien</th>
                        <th>Tanggal Janji</th>
                        <th>Dokter Pilihan</th>
                        <th>No. HP</th>
                        <th>Keluhan & Tindakan</th>
                        <th>Total Biaya</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($pasien) === 0 || Session::has('message'))
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">Tidak Ada Data Pasien!</td>
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
                        <th scope="row">{{ $loop->iteration }}</th> 
                        <td>
                            <strong>{{ $data->nama_pasien }}</strong><br>
                            <small class="text-muted">{{ $data->formatted_id }} | {{ $data->email_pasien }}</small>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($data->tanggal_janji)->format('d M Y H:i') }}</td>
                        <td>{{ $data->dokter_pilihan ?? '-' }}</td>
                        <td>
                            <a href="https://wa.me/{{ $cleanHp }}?text={{ $waMsg }}" target="_blank" class="btn btn-xs btn-outline-success" title="Chat WhatsApp">
                                <i class="bx bxl-whatsapp fs-6"></i> {{ $data->no_hp_pasien }}
                            </a>
                        </td>
                        <td>
                            <small><strong>Keluhan:</strong> {{ Str::limit($data->keluhan_pasien, 30) }}</small><br>
                            <small class="text-primary"><strong>Tindakan:</strong> {{ $data->tindakan_pasien ?? '-' }}</small>
                        </td>
                        <td class="fw-bold">
                            {{ $data->total_harga_pasien ? 'Rp '.number_format((float) str_replace(['Rp', '.', ' '], '', $data->total_harga_pasien), 0, ',', '.') : '-' }}
                        </td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn btn-xs 
                                    @if($data->status === 'completed') btn-success
                                    @elseif($data->status === 'confirmed') btn-info
                                    @elseif($data->status === 'cancelled') btn-danger
                                    @else btn-warning text-dark @endif
                                    dropdown-toggle" data-bs-toggle="dropdown">
                                    {{ ucfirst($data->status ?? 'pending') }}
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="/admin-area/pasien/status/{{ Crypt::encrypt($data->id_pasien) }}/pending"><span class="badge bg-warning text-dark me-2">Pending</span> Menunggu</a></li>
                                    <li><a class="dropdown-item" href="/admin-area/pasien/status/{{ Crypt::encrypt($data->id_pasien) }}/confirmed"><span class="badge bg-info me-2">Confirmed</span> Konfirmasi</a></li>
                                    <li><a class="dropdown-item" href="/admin-area/pasien/status/{{ Crypt::encrypt($data->id_pasien) }}/completed"><span class="badge bg-success me-2">Completed</span> Selesai</a></li>
                                    <li><a class="dropdown-item" href="/admin-area/pasien/status/{{ Crypt::encrypt($data->id_pasien) }}/cancelled"><span class="badge bg-danger me-2">Cancelled</span> Batal</a></li>
                                </ul>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a class="btn btn-sm btn-outline-primary" href="/admin-area/pasien/edit/{{ Crypt::encrypt($data->id_pasien) }}">
                                    <i class="bx bx-edit-alt"></i> Edit
                                </a>
                                <a class="btn btn-sm btn-outline-info" href="/admin-area/pasien/invoice/{{ Crypt::encrypt($data->id_pasien) }}" target="_blank">
                                    <i class="bx bx-receipt"></i> Invoice
                                </a>
                                <a class="btn btn-sm btn-outline-danger" href="/admin-area/pasien/delete/{{ Crypt::encrypt($data->id_pasien) }}" onclick="return confirm('Hapus data pasien {{ $data->nama_pasien }}?')">
                                    <i class="bx bx-trash"></i> Hapus
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        
        <div class="p-3">
            {{ $pasien->links('admin.layout.pagination') }}
        </div>
    </div>
</div>
@endsection
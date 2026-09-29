<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Pembayaran — {{ $pasien->nama_pasien }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .invoice-card {
            max-width: 800px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 40px;
        }
        .invoice-header {
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .clinic-brand h2 {
            font-weight: 700;
            color: #0d6efd;
            margin-bottom: 4px;
        }
        .invoice-title {
            font-size: 28px;
            font-weight: 700;
            color: #333;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .badge-status {
            font-size: 14px;
            padding: 6px 14px;
            border-radius: 50px;
        }
        .table-invoice th {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: 600;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            .invoice-card {
                box-shadow: none;
                margin: 0;
                padding: 20px;
                max-width: 100%;
            }
            body {
                background: #fff;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="no-print text-center mt-4">
            <button onclick="window.print()" class="btn btn-primary me-2"><i class="bx bx-printer"></i> Cetak Invoice / Save PDF</button>
            <a href="javascript:history.back()" class="btn btn-secondary"><i class="bx bx-arrow-back"></i> Kembali</a>
        </div>

        <div class="invoice-card">
            <div class="invoice-header d-flex justify-content-between align-items-center">
                <div class="clinic-brand">
                    <h2>Klinik FAM Dental Care</h2>
                    <p class="text-muted mb-0">Jl. Healthcare No. 123, Bandung | Telp: (022) 123-4567</p>
                </div>
                <div class="text-end">
                    <div class="invoice-title">INVOICE</div>
                    <div class="text-muted">No: {{ $pasien->formatted_id }}</div>
                    <div class="text-muted">Tanggal: {{ \Carbon\Carbon::parse($pasien->tanggal_janji)->format('d/m/Y H:i') }}</div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="text-uppercase text-muted fw-bold">Ditujukan Kepada:</h6>
                    <h5 class="fw-bold text-dark mb-1">{{ $pasien->nama_pasien }}</h5>
                    <p class="mb-1"><strong>Email:</strong> {{ $pasien->email_pasien }}</p>
                    <p class="mb-1"><strong>No. HP:</strong> {{ $pasien->no_hp_pasien }}</p>
                    <p class="mb-0"><strong>Alamat:</strong> {{ $pasien->alamat_pasien }}</p>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    <h6 class="text-uppercase text-muted fw-bold">Detail Reservasi:</h6>
                    <p class="mb-1"><strong>Dokter Penanggung Jawab:</strong> {{ $pasien->dokter_pilihan ?? 'Dokter Klinik' }}</p>
                    <p class="mb-1">
                        <strong>Status Pembayaran/Janji:</strong>
                        @if ($pasien->status === 'completed')
                            <span class="badge bg-success badge-status">Selesai / Lunas</span>
                        @elseif ($pasien->status === 'confirmed')
                            <span class="badge bg-info badge-status">Dikonfirmasi</span>
                        @elseif ($pasien->status === 'cancelled')
                            <span class="badge bg-danger badge-status">Dibatalkan</span>
                        @else
                            <span class="badge bg-warning text-dark badge-status">Pending</span>
                        @endif
                    </p>
                </div>
            </div>

            <div class="table-responsive mb-4">
                <table class="table table-bordered table-invoice align-middle">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Deskripsi Perawatan / Tindakan</th>
                            <th>Keluhan Pasien</th>
                            <th class="text-end" style="width: 180px;">Total Biaya</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>
                                <strong>{{ $pasien->tindakan_pasien ?? 'Pemeriksaan & Konsultasi Gigi' }}</strong>
                            </td>
                            <td>{{ $pasien->keluhan_pasien }}</td>
                            <td class="text-end fw-bold">
                                Rp {{ number_format((float) str_replace(['Rp', '.', ' '], '', $pasien->total_harga_pasien ?? 0), 0, ',', '.') }}
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end fw-bold fs-5">TOTAL BIAYA:</td>
                            <td class="text-end fw-bold fs-5 text-primary">
                                Rp {{ number_format((float) str_replace(['Rp', '.', ' '], '', $pasien->total_harga_pasien ?? 0), 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="row mt-5 pt-3">
                <div class="col-md-6">
                    <p class="small text-muted mb-0">Catatan:</p>
                    <p class="small text-muted">Invoice ini merupakan bukti resmi reservasi dan perawatan medis di Klinik FAM Dental Care.</p>
                </div>
                <div class="col-md-6 text-center">
                    <p class="mb-5">Bandung, {{ date('d M Y') }}<br><strong>Kasir / Admin Klinik</strong></p>
                    <div class="mt-4 border-bottom d-inline-block" style="width: 180px;"></div>
                    <p class="small text-muted mt-1">(Stempel & Tanda Tangan)</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

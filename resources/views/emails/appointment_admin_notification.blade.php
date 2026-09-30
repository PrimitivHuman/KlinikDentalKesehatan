<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Janji Temu Baru</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; margin: 0; padding: 20px; color: #333; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #696cff, #5f61e6); color: white; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 22px; }
        .badge { display: inline-block; background: #ff6b6b; color: white; padding: 4px 12px; border-radius: 20px; font-size: 13px; margin-top: 8px; }
        .body { padding: 30px; }
        .alert-box { background: #fff3cd; border-left: 4px solid #ffc107; padding: 12px 16px; border-radius: 4px; margin-bottom: 24px; font-size: 14px; }
        .info-table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        .info-table th { background: #f8f9fa; text-align: left; padding: 10px 14px; font-size: 13px; color: #666; border: 1px solid #e9ecef; width: 35%; }
        .info-table td { padding: 10px 14px; font-size: 14px; border: 1px solid #e9ecef; }
        .status-badge { display: inline-block; background: #e0e0ff; color: #696cff; padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; text-transform: uppercase; }
        .btn { display: inline-block; background: #696cff; color: white; padding: 12px 28px; border-radius: 6px; text-decoration: none; font-weight: bold; margin-top: 20px; }
        .footer { background: #f8f9fa; text-align: center; padding: 16px; font-size: 12px; color: #888; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🦷 Klinik FAM Dental Care</h1>
            <div class="badge">🔔 Janji Temu Baru Masuk!</div>
        </div>
        <div class="body">
            <div class="alert-box">
                Ada pendaftaran janji temu baru yang memerlukan konfirmasi Anda.
            </div>

            <h3 style="margin-top:0; color: #696cff;">Detail Pendaftaran</h3>
            <table class="info-table">
                <tr>
                    <th>Nama Pasien</th>
                    <td><strong>{{ $pasien->nama_pasien }}</strong></td>
                </tr>
                <tr>
                    <th>Tanggal Janji</th>
                    <td>{{ \Carbon\Carbon::parse($pasien->tanggal_janji)->isoFormat('dddd, D MMMM Y') }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $pasien->email_pasien }}</td>
                </tr>
                <tr>
                    <th>No. HP</th>
                    <td>{{ $pasien->no_hp_pasien }}</td>
                </tr>
                <tr>
                    <th>Alamat</th>
                    <td>{{ $pasien->alamat_pasien }}</td>
                </tr>
                <tr>
                    <th>Dokter Pilihan</th>
                    <td>{{ $pasien->dokter_pilihan ?: '-' }}</td>
                </tr>
                <tr>
                    <th>Keluhan</th>
                    <td>{{ $pasien->keluhan_pasien }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td><span class="status-badge">{{ $pasien->status }}</span></td>
                </tr>
            </table>

            <center>
                <a href="{{ url('/admin-area/pasien') }}" class="btn">Buka Panel Admin →</a>
            </center>
        </div>
        <div class="footer">
            Email ini dikirim otomatis oleh sistem. Harap tidak membalas email ini.<br>
            © {{ date('Y') }} Klinik FAM Dental Care
        </div>
    </div>
</body>
</html>

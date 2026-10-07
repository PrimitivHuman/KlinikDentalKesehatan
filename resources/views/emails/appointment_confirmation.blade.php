<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Janji Temu</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 20px;
            color: #333333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }
        .header {
            background-color: #0d6efd;
            color: #ffffff;
            padding: 25px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 600;
        }
        .content {
            padding: 30px 25px;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
        }
        .details-box {
            background-color: #f8f9fa;
            border-left: 4px solid #0d6efd;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .details-item {
            margin-bottom: 8px;
            font-size: 14px;
        }
        .details-item strong {
            color: #495057;
            display: inline-block;
            width: 140px;
        }
        .footer {
            background-color: #f1f3f5;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Klinik FAM Dental Care</h1>
        </div>
        <div class="content">
            <p class="greeting">Halo, <strong>{{ $pasien->nama_pasien }}</strong>!</p>
            <p>Terima kasih telah melakukan pendaftaran janji temu di Klinik FAM Dental Care. Berikut adalah rincian pendaftaran Anda:</p>

            <div class="details-box">
                <div class="details-item">
                    <strong>Nama Pasien:</strong> {{ $pasien->nama_pasien }}
                </div>
                <div class="details-item">
                    <strong>Email:</strong> {{ $pasien->email_pasien }}
                </div>
                <div class="details-item">
                    <strong>No. Telepon:</strong> {{ $pasien->no_hp_pasien }}
                </div>
                <div class="details-item">
                    <strong>Jadwal Janji Temu:</strong> {{ \Carbon\Carbon::parse($pasien->tanggal_janji)->format('d M Y, H:i') }} WIB
                </div>
                <div class="details-item">
                    <strong>Dokter:</strong> {{ $pasien->dokter_pilihan ?: 'Ditentukan oleh klinik' }}
                </div>
                <div class="details-item">
                    <strong>Keluhan:</strong> {{ $pasien->keluhan_pasien ?? '-' }}
                </div>
            </div>

            <p>Tim klinik kami akan menghubungi Anda melalui WhatsApp/Telepon untuk mengonfirmasi jam dan detail kedatangan Anda.</p>
            <p>Jika ada pertanyaan atau perubahan jadwal, silakan hubungi kontak klinik kami.</p>
            <br>
            <p>Salam hangat,<br><strong>Klinik FAM Dental Care</strong></p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Klinik FAM Dental Care. All rights reserved.
        </div>
    </div>
</body>
</html>

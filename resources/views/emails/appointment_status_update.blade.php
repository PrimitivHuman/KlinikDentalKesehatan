<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembaruan Janji Temu</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f4f6f9; margin: 0; padding: 20px; color: #333; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,.05); }
        .header { background-color: #0d9488; color: #fff; padding: 25px; text-align: center; }
        .header h1 { margin: 0; font-size: 22px; font-weight: 600; }
        .content { padding: 30px 25px; }
        .details-box { background: #f8f9fa; border-left: 4px solid #0d9488; padding: 15px 20px; margin: 20px 0; border-radius: 4px; font-size: 14px; }
        .details-box div { margin-bottom: 8px; }
        .footer { background: #f1f3f5; padding: 15px; text-align: center; font-size: 12px; color: #6c757d; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header"><h1>Klinik FAM Dental Care</h1></div>
        <div class="content">
            <p>Halo, <strong>{{ $pasien->nama_pasien }}</strong>!</p>

            @if ($pasien->status === 'confirmed')
                <p>Kabar baik! Janji temu Anda telah <strong>dikonfirmasi</strong>. Mohon hadir tepat waktu sesuai jadwal berikut:</p>
            @elseif ($pasien->status === 'completed')
                <p>Terima kasih telah mempercayakan perawatan gigi Anda kepada kami. Perawatan Anda telah <strong>selesai</strong>.</p>
            @elseif ($pasien->status === 'cancelled')
                <p>Mohon maaf, janji temu Anda telah <strong>dibatalkan</strong>. Silakan hubungi kami atau buat janji baru melalui website.</p>
            @else
                <p>Status janji temu Anda diperbarui menjadi <strong>{{ $pasien->status }}</strong>.</p>
            @endif

            <div class="details-box">
                <div><strong>Jadwal:</strong> {{ \Carbon\Carbon::parse($pasien->tanggal_janji)->format('d M Y, H:i') }} WIB</div>
                <div><strong>Dokter:</strong> {{ $pasien->dokter_pilihan ?: 'Ditentukan oleh klinik' }}</div>
                <div><strong>Alamat:</strong> Jl. Leuwi Panjang No.52a, Bojongloa Kidul, Kota Bandung</div>
            </div>

            <p>Salam hangat,<br><strong>Klinik FAM Dental Care</strong></p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Klinik FAM Dental Care. Seluruh hak cipta dilindungi.
        </div>
    </div>
</body>
</html>

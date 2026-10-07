@echo off
title Cloudflare Tunnel - FAM Dental Care
cls
echo =========================================================================
echo               KLINIK FAM DENTAL CARE - CLOUDFLARE TUNNEL
echo =========================================================================
echo.
echo [1/2] Menghubungkan ke Laragon Apache (KlinikDentalKesehatan.dev)...
echo [2/2] Membuka Cloudflare Tunnel ke Internet via protocol HTTP/2...
echo.
echo -------------------------------------------------------------------------
echo Silakan tunggu beberapa detik hingga URL publik (format di bawah) muncul:
echo    https://xxxx-xxxx-xxxx.trycloudflare.com
echo.
echo Bagikan link https://... tersebut kepada klien atau teman Anda.
echo Seluruh gambar, CSS, JS, dan Form otomatis aman (HTTPS) tanpa error!
echo.
echo Tekan [Ctrl + C] kapan saja di jendela ini untuk menghentikan tunnel.
echo -------------------------------------------------------------------------
echo.

cloudflared tunnel --protocol http2 --http-host-header KlinikDentalKesehatan.dev --url http://127.0.0.1:80

pause

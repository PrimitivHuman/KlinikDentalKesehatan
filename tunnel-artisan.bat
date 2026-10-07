@echo off
title Cloudflare Tunnel (Artisan Serve) - FAM Dental Care
cls
echo =========================================================================
echo         KLINIK FAM DENTAL CARE - TUNNEL (PHP ARTISAN SERVE)
echo =========================================================================
echo.
echo Pastikan Anda sudah menjalankan 'php artisan serve' di terminal lain!
echo Menghubungkan tunnel ke http://127.0.0.1:8000 ...
echo.
cloudflared tunnel --protocol http2 --url http://127.0.0.1:8000
pause

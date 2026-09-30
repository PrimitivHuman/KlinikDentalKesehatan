<?php

namespace App\Mail;

use App\Models\Pasien;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * R1: Mailable untuk notifikasi ke admin saat ada pendaftaran janji temu baru.
 */
class AppointmentAdminNotification extends Mailable
{
    use Queueable, SerializesModels;

    public Pasien $pasien;

    /**
     * @param Pasien $pasien Data pasien yang baru mendaftar
     */
    public function __construct(Pasien $pasien)
    {
        $this->pasien = $pasien;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[Notifikasi] Janji Temu Baru: ' . $this->pasien->nama_pasien,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.appointment_admin_notification',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

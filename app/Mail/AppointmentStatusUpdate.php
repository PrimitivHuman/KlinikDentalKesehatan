<?php

namespace App\Mail;

use App\Models\Pasien;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Email pemberitahuan ke pasien saat status janji temu berubah
 * (dikonfirmasi / selesai / dibatalkan).
 */
class AppointmentStatusUpdate extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Pasien $pasien)
    {
    }

    public function envelope(): Envelope
    {
        $subjects = [
            'confirmed' => 'Janji Temu Anda Dikonfirmasi',
            'completed' => 'Terima Kasih Telah Berkunjung',
            'cancelled' => 'Janji Temu Anda Dibatalkan',
        ];

        return new Envelope(
            subject: ($subjects[$this->pasien->status] ?? 'Pembaruan Janji Temu') . ' — Klinik FAM Dental Care',
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.appointment_status_update');
    }
}

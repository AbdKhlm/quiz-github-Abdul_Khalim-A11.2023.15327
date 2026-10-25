<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentUploadedNotification extends Notification
{
    use Queueable;

    private $periksa;
    private $pasien;

    public function __construct($periksa, $pasien)
    {
        $this->periksa = $periksa;
        $this->pasien = $pasien;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'message' => 'Pasien ' . $this->pasien->nama . ' telah mengunggah bukti pembayaran untuk pemeriksaan pada ' . $this->periksa->tgl_periksa . '.',
            'periksa_id' => $this->periksa->id,
            'pasien_id' => $this->pasien->id,
            'type' => 'info',
        ];
    }
}

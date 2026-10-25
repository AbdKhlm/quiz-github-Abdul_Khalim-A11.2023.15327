<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentVerifiedNotification extends Notification
{
    use Queueable;

    private $periksa;

    public function __construct($periksa)
    {
        $this->periksa = $periksa;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'message' => 'Pembayaran Anda untuk pemeriksaan pada ' . $this->periksa->tgl_periksa . ' telah diverifikasi oleh Admin (Lunas).',
            'periksa_id' => $this->periksa->id,
            'type' => 'success',
        ];
    }
}

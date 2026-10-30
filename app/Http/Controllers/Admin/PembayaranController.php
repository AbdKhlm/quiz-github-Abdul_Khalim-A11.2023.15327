<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Periksa;
use App\Notifications\PaymentVerifiedNotification;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index()
    {
        $pemeriksaans = Periksa::with(['daftarPoli.pasien', 'daftarPoli.jadwalPeriksa.dokter.poli'])
            ->where('status_pembayaran', 'verification')
            ->orderBy('updated_at', 'asc')
            ->get();

        return view('admin.pembayaran.index', compact('pemeriksaans'));
    }

    public function verify($id)
    {
        $periksa = Periksa::findOrFail($id);

        // Hanya pembayaran yang sudah diunggah buktinya yang boleh diverifikasi.
        if ($periksa->status_pembayaran !== 'verification' || !$periksa->bukti_bayar) {
            return back()->with('error', 'Pembayaran ini tidak dapat diverifikasi.');
        }

        $pasien = $periksa->daftarPoli?->pasien;

        if (!$pasien) {
            return back()->with('error', 'Data pasien tidak ditemukan.');
        }

        $periksa->status_pembayaran = 'paid';
        $periksa->save();

        // Kirim notifikasi ke Pasien.
        $pasien->notify(new PaymentVerifiedNotification($periksa));

        return back()->with('success', 'Pembayaran pasien ' . $pasien->nama . ' telah diverifikasi.');
    }
}

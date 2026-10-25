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
        $pemeriksaans = Periksa::with(['daftarPoli.pasien', 'daftarPoli.jadwalPeriksa.dokter'])
            ->where('status_pembayaran', 'verification')
            ->orderBy('updated_at', 'asc')
            ->get();

        return view('admin.pembayaran.index', compact('pemeriksaans'));
    }

    public function verify($id)
    {
        $periksa = Periksa::findOrFail($id);
        $periksa->status_pembayaran = 'paid';
        $periksa->save();

        // Kirim notifikasi ke Pasien
        $pasien = $periksa->daftarPoli->pasien;
        $pasien->notify(new PaymentVerifiedNotification($periksa));

        return back()->with('success', 'Pembayaran pasien ' . $pasien->nama . ' telah diverifikasi.');
    }
}

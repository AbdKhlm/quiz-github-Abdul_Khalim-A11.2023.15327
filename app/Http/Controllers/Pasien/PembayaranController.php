<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\Periksa;
use App\Notifications\PaymentUploadedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    public function index()
    {
        $pasienId = Auth::id();
        $pemeriksaans = Periksa::whereHas('daftarPoli', function ($query) use ($pasienId) {
            $query->where('id_pasien', $pasienId);
        })->with(['daftarPoli.jadwalPeriksa.dokter'])->orderBy('tgl_periksa', 'desc')->get();

        return view('pasien.pembayaran.index', compact('pemeriksaans'));
    }

    public function upload(Request $request, $id)
    {
        $request->validate([
            'bukti_bayar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $periksa = Periksa::findOrFail($id);
        
        // Simpan file
        if ($request->hasFile('bukti_bayar')) {
            $path = $request->file('bukti_bayar')->store('bukti_bayar', 'public');
            $periksa->bukti_bayar = $path;
            $periksa->status_pembayaran = 'verification';
            $periksa->save();

            // Kirim notifikasi ke Dokter
            $dokter = $periksa->daftarPoli->jadwalPeriksa->dokter;
            $pasien = Auth::user();
            $dokter->notify(new PaymentUploadedNotification($periksa, $pasien));
        }

        return back()->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi Admin.');
    }
}

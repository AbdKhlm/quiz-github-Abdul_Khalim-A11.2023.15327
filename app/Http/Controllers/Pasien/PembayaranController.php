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
        })
            ->with(['daftarPoli.jadwalPeriksa.dokter.poli'])
            ->orderBy('tgl_periksa', 'desc')
            ->get();

        return view('pasien.pembayaran.index', compact('pemeriksaans'));
    }

    public function upload(Request $request, $id)
    {
        $request->validate([
            'bukti_bayar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'bukti_bayar.required' => 'Bukti pembayaran wajib diunggah.',
            'bukti_bayar.image' => 'Berkas yang diunggah harus berupa gambar.',
            'bukti_bayar.mimes' => 'Format gambar harus JPG, JPEG, atau PNG.',
            'bukti_bayar.max' => 'Ukuran gambar maksimal 2 MB.',
        ]);

        $pasien = Auth::user();

        // Pastikan pemeriksaan ini memang milik pasien yang sedang login.
        $periksa = Periksa::whereHas('daftarPoli', function ($query) use ($pasien) {
            $query->where('id_pasien', $pasien->id);
        })->findOrFail($id);

        if ($periksa->status_pembayaran === 'paid') {
            return back()->with('error', 'Pembayaran pemeriksaan ini sudah lunas dan tidak dapat diubah lagi.');
        }

        if ($request->hasFile('bukti_bayar')) {
            // Hapus bukti lama agar tidak menumpuk di storage.
            if ($periksa->bukti_bayar) {
                Storage::disk('public')->delete($periksa->bukti_bayar);
            }

            $periksa->bukti_bayar = $request->file('bukti_bayar')->store('bukti_bayar', 'public');
            $periksa->status_pembayaran = 'verification';
            $periksa->save();

            // Kirim notifikasi ke Dokter pemeriksa.
            $dokter = $periksa->daftarPoli?->jadwalPeriksa?->dokter;

            if ($dokter) {
                $dokter->notify(new PaymentUploadedNotification($periksa, $pasien));
            }
        }

        return back()->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi Admin.');
    }
}

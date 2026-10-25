<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PoliController extends Controller
{
    public function get()
    {
        $user = Auth::user();

        // Cek apakah pasien sudah memiliki antrian aktif (belum diperiksa)
        $antrianAktif = DaftarPoli::where('id_pasien', $user->id)
            ->whereDoesntHave('periksas')
            ->exists();

        if ($antrianAktif) {
            return redirect()
                ->route('pasien.dashboard')
                ->with('error', 'Anda masih memiliki antrian aktif. Silakan selesaikan pemeriksaan sebelumnya sebelum mendaftar kembali.');
        }

        $polis = Poli::all();
        $jadwal = JadwalPeriksa::with('dokter', 'dokter.poli')->get();

        return view('pasien.daftar', [
            'user' => $user,
            'polis' => $polis,
            'jadwals' => $jadwal,
        ]);
    }

    public function submit(Request $request)
    {
        $request->validate([
            'id_poli' => 'required|exists:poli,id',
            'id_jadwal' => 'required|exists:jadwal_periksa,id',
            'keluhan' => 'nullable|string',
            'id_pasien' => 'required|exists:users,id',
        ]);

        // Proteksi tambahan di sisi server
        $antrianAktif = DaftarPoli::where('id_pasien', $request->id_pasien)
            ->whereDoesntHave('periksas')
            ->exists();

        if ($antrianAktif) {
            return redirect()
                ->route('pasien.dashboard')
                ->with('error', 'Gagal mendaftar. Anda masih memiliki antrian aktif.');
        }

        $jumlahSudahDaftar = DaftarPoli::where('id_jadwal', $request->id_jadwal)->count();

        DaftarPoli::create([
            'id_pasien' => $request->id_pasien,
            'id_jadwal' => $request->id_jadwal,
            'keluhan' => $request->keluhan,
            'no_antrian' => $jumlahSudahDaftar + 1,
        ]);

        return redirect()
            ->route('pasien.dashboard')
            ->with('success', 'Berhasil mendaftar ke poli. Silakan pantau nomor antrian Anda.');
    }
}

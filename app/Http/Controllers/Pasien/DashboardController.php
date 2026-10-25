<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $pasienId = Auth::id();

        // Ambil antrian aktif (yang belum diperiksa)
        $antrianAktif = DaftarPoli::with(['jadwalPeriksa.dokter.poli'])
            ->where('id_pasien', $pasienId)
            ->whereDoesntHave('periksas')
            ->first();

        // Ambil semua jadwal untuk tabel
        $jadwals = JadwalPeriksa::with(['dokter.poli'])->get();

        // Hitung nomor yang sedang dilayani untuk setiap jadwal
        foreach ($jadwals as $jadwal) {
            $jadwal->antrian_sekarang = DaftarPoli::where('id_jadwal', $jadwal->id)
                ->whereHas('periksas')
                ->max('no_antrian') ?? 0;
        }

        // Jika ada antrian aktif, hitung juga antrian sekarang untuk antrian tersebut
        if ($antrianAktif) {
            $antrianAktif->antrian_sekarang = DaftarPoli::where('id_jadwal', $antrianAktif->id_jadwal)
                ->whereHas('periksas')
                ->max('no_antrian') ?? 0;
        }

        return view('pasien.dashboard', compact('antrianAktif', 'jadwals'));
    }

    /**
     * API untuk live update nomor antrian
     */
    public function getLiveUpdate()
    {
        $jadwals = JadwalPeriksa::select('id')->get();
        $updates = [];

        foreach ($jadwals as $jadwal) {
            $updates[$jadwal->id] = DaftarPoli::where('id_jadwal', $jadwal->id)
                ->whereHas('periksas')
                ->max('no_antrian') ?? 0;
        }

        return response()->json($updates);
    }
}

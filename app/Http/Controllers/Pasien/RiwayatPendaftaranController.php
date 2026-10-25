<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use Illuminate\Support\Facades\Auth;

class RiwayatPendaftaranController extends Controller
{
    public function index()
    {
        $riwayat = DaftarPoli::with([
            'jadwalPeriksa.dokter.poli',
            'periksas'
        ])
            ->where('id_pasien', Auth::id())
            ->latest()
            ->paginate(10);

        return view('pasien.riwayat-pendaftaran.index', compact('riwayat'));
    }

    public function show($id)
    {
        $pendaftaran = DaftarPoli::with([
            'jadwalPeriksa.dokter.poli',
            'periksas.detailPeriksas.obat'
        ])
            ->where('id_pasien', Auth::id())
            ->findOrFail($id);

        $periksa = $pendaftaran->periksas
            ->sortByDesc('tgl_periksa')
            ->first();

        // Detail hanya bisa dilihat setelah pemeriksaan tercatat.
        abort_if(!$periksa, 404);

        return view(
            'pasien.riwayat-pendaftaran.show',
            compact('pendaftaran', 'periksa')
        );
    }
}

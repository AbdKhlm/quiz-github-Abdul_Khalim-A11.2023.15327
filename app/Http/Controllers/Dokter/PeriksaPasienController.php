<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\DetailPeriksa;
use App\Models\Obat;
use App\Models\Periksa;
use App\Events\QueueUpdated;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeriksaPasienController extends Controller
{
    public function index()
    {
        $dokterId = Auth::id();

        $daftarPasien = DaftarPoli::with(['pasien', 'jadwalPeriksa', 'periksas'])
            ->whereHas('jadwalPeriksa', function ($query) use ($dokterId) {
                $query->where('id_dokter', $dokterId);
            })
            ->orderBy('no_antrian')
            ->get();

        return view('dokter.periksa-pasien.index', compact('daftarPasien'));
    }

    public function create($id)
    {
        $obats = Obat::all();
        return view('dokter.periksa-pasien.create', compact('obats', 'id'));
    }

    public function store(Request $request)
    {
    $data = $request->validate([
        'id_daftar_poli' => 'required|exists:daftar_poli,id',
        'obat_json' => 'required|json',
        'catatan' => 'nullable|string',
        'biaya_periksa' => 'required|integer|min:0',
    ]);

    $obatIds = json_decode($data['obat_json'], true);

    if (!is_array($obatIds)) {
        return back()->withErrors([
            'obat_json' => 'Format data obat tidak valid.',
        ])->withInput();
    }

    $pendaftaran = DaftarPoli::where('id', $data['id_daftar_poli'])
        ->whereHas('jadwalPeriksa', function ($query) {
            $query->where('id_dokter', Auth::id());
        })
        ->firstOrFail();

    if ($pendaftaran->periksas()->exists()) {
        return back()->withErrors([
            'id_daftar_poli' => 'Pasien ini sudah diperiksa.',
        ]);
    }

    DB::transaction(function () use ($data, $obatIds, $pendaftaran) {
        $periksa = Periksa::create([
            'id_daftar_poli' => $pendaftaran->id,
            'tgl_periksa' => now(),
            'catatan' => $data['catatan'] ?? null,
            'biaya_periksa' => $data['biaya_periksa'] + 150000,
        ]);

        foreach ($obatIds as $idObat) {
            DetailPeriksa::create([
                'id_periksa' => $periksa->id,
                'id_obat' => $idObat,
            ]);
        }
    });

    $antrianSekarang = (int) DaftarPoli::where(
        'id_jadwal',
        $pendaftaran->id_jadwal
    )
        ->whereHas('periksas')
        ->max('no_antrian');

    QueueUpdated::dispatch(
        (int) $pendaftaran->id_jadwal,
        $antrianSekarang
    );

    return redirect()
        ->route('periksa-pasien.index')
        ->with('success', 'Data periksa berhasil disimpan.');
    }
}
<?php

namespace App\Exports;

use App\Models\Periksa;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RiwayatPasienExport implements FromCollection, WithHeadings, WithMapping
{
    protected $id_dokter;

    public function __construct($id_dokter)
    {
        $this->id_dokter = $id_dokter;
    }

    public function collection(): Enumerable
    {
        return Periksa::whereHas('daftarPoli.jadwalPeriksa', function ($query) {
            $query->where('id_dokter', $this->id_dokter);
        })->with([
            'daftarPoli.pasien',
            'detailPeriksas.obat'
        ])->orderBy('tgl_periksa', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'ID Periksa',
            'Tanggal Periksa',
            'Nama Pasien',
            'No. RM',
            'Keluhan',
            'Catatan Dokter',
            'Daftar Obat',
            'Total Biaya',
        ];
    }

    public function map($periksa): array
    {
        $obatList = $periksa->detailPeriksas->map(function ($detail) {
            return $detail->obat->nama_obat;
        })->implode(', ');

        return [
            $periksa->id,
            $periksa->tgl_periksa,
            $periksa->daftarPoli->pasien->nama ?? '-',
            $periksa->daftarPoli->pasien->no_rm ?? '-',
            $periksa->daftarPoli->keluhan ?? '-',
            $periksa->catatan ?? '-',
            $obatList ?: '-',
            $periksa->biaya_periksa,
        ];
    }
}

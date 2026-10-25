<?php

namespace App\Exports;

use App\Models\JadwalPeriksa;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class JadwalPeriksaExport implements FromCollection, WithHeadings, WithMapping
{
    protected $id_dokter;

    public function __construct($id_dokter)
    {
        $this->id_dokter = $id_dokter;
    }

    public function collection(): Enumerable
    {
        return JadwalPeriksa::where('id_dokter', $this->id_dokter)->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Hari',
            'Jam Mulai',
            'Jam Selesai',
        ];
    }

    public function map($jadwal): array
    {
        return [
            $jadwal->id,
            $jadwal->hari,
            $jadwal->jam_mulai,
            $jadwal->jam_selesai,
        ];
    }
}

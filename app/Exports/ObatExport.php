<?php

namespace App\Exports;

use App\Models\Obat;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ObatExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection(): Enumerable
    {
        return Obat::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Obat',
            'Kemasan',
            'Stok',
            'Harga',
        ];
    }

    public function map($obat): array
    {
        return [
            $obat->id,
            $obat->nama_obat,
            $obat->kemasan,
            $obat->stok,
            $obat->harga,
        ];
    }
}

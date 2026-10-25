<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DokterExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection(): Enumerable
    {
        return User::where('role', 'dokter')->with('poli')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Dokter',
            'Email',
            'Alamat',
            'No. KTP',
            'No. HP',
            'Poli',
        ];
    }

    public function map($user): array
    {
        return [
            $user->id,
            $user->nama,
            $user->email,
            $user->alamat,
            $user->no_ktp,
            $user->no_hp,
            $user->poli->nama_poli ?? '-',
        ];
    }
}

<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PasienExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection(): Enumerable
    {
        return User::where('role', 'pasien')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Pasien',
            'No. RM',
            'Email',
            'Alamat',
            'No. KTP',
            'No. HP',
        ];
    }

    public function map($user): array
    {
        return [
            $user->id,
            $user->nama,
            $user->no_rm,
            $user->email,
            $user->alamat,
            $user->no_ktp,
            $user->no_hp,
        ];
    }
}

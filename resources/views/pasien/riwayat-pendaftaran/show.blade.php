
<x-layouts.app title="Detail Riwayat Pemeriksaan">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('pasien.riwayat.index') }}"
            class="btn btn-sm bg-slate-100 border-0">
            <i class="fas fa-arrow-left"></i>
            Kembali
        </a>

        <h2 class="text-2xl font-bold text-slate-800">
            Detail Pemeriksaan
        </h2>
    </div>

    <div class="space-y-5">

        <div class="card bg-white border border-slate-200 shadow-sm rounded-2xl">
            <div class="card-body">
                <h3 class="font-bold text-lg">Informasi Pendaftaran</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-slate-500">Nama Pasien</p>
                        <p class="font-semibold">
                            {{ $pendaftaran->pasien?->nama ?? auth()->user()->nama }}
                        </p>
                    </div>

                    <div>
                        <p class="text-slate-500">Nomor Antrean</p>
                        <p class="font-semibold">{{ $pendaftaran->no_antrian }}</p>
                    </div>

                    <div>
                        <p class="text-slate-500">Poli</p>
                        <p class="font-semibold">
                            {{ $pendaftaran->jadwalPeriksa?->dokter?->poli?->nama_poli ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-slate-500">Dokter</p>
                        <p class="font-semibold">
                            {{ $pendaftaran->jadwalPeriksa?->dokter?->nama ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-slate-500">Tanggal Pemeriksaan</p>
                        <p class="font-semibold">
                            {{ \Carbon\Carbon::parse($periksa->tgl_periksa)->format('d/m/Y H:i') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-slate-500">Keluhan</p>
                        <p class="font-semibold">{{ $pendaftaran->keluhan }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-white border border-slate-200 shadow-sm rounded-2xl">
            <div class="card-body">
                <h3 class="font-bold text-lg">Catatan Dokter</h3>
                <p class="text-slate-600 whitespace-pre-line">
                    {{ $periksa->catatan ?: 'Tidak ada catatan dokter.' }}
                </p>
            </div>
        </div>

        <div class="card bg-white border border-slate-200 shadow-sm rounded-2xl">
            <div class="card-body">
                <h3 class="font-bold text-lg">Daftar Obat</h3>

                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Obat</th>
                                <th>Harga</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($periksa->detailPeriksas as $detail)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $detail->obat?->nama_obat ?? '-' }}</td>
                                    <td>
                                        Rp {{ number_format($detail->obat?->harga ?? 0, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-slate-400">
                                        Tidak ada obat yang diresepkan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t pt-4 flex justify-between items-center">
                    <span class="font-bold">Total Biaya Pemeriksaan</span>
                    <span class="text-xl font-bold text-blue-700">
                        Rp {{ number_format($periksa->biaya_periksa, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

    </div>

</x-layouts.app>

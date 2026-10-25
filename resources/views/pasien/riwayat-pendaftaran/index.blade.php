
<x-layouts.app title="Riwayat Pendaftaran">

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-800">
            Riwayat Pendaftaran Poli
        </h2>
        <p class="text-sm text-slate-500 mt-1">
            Daftar pendaftaran dan status pemeriksaan Anda.
        </p>
    </div>

    <div class="card bg-white shadow-md rounded-2xl border border-slate-200">
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead class="bg-slate-100 text-slate-500 text-xs uppercase">
                    <tr>
                        <th>No.</th>
                        <th>Tanggal Daftar</th>
                        <th>Poli</th>
                        <th>Dokter</th>
                        <th>Jadwal</th>
                        <th>No. Antrean</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($riwayat as $item)
                        @php
                            $jadwal = $item->jadwalPeriksa;
                            $dokter = $jadwal?->dokter;
                            $sudahDiperiksa = $item->periksas->isNotEmpty();
                        @endphp

                        <tr>
                            <td>{{ $loop->iteration + $riwayat->firstItem() - 1 }}</td>

                            <td>
                                {{ $item->created_at?->format('d/m/Y') ?? '-' }}
                            </td>

                            <td>
                                {{ $dokter?->poli?->nama_poli ?? '-' }}
                            </td>

                            <td>{{ $dokter?->nama ?? '-' }}</td>

                            <td>
                                {{ $jadwal?->hari ?? '-' }}<br>
                                <span class="text-xs text-slate-500">
                                    {{ $jadwal?->jam_mulai ? \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') : '-' }}
                                    -
                                    {{ $jadwal?->jam_selesai ? \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') : '-' }}
                                </span>
                            </td>

                            <td class="font-bold">
                                {{ $item->no_antrian }}
                            </td>

                            <td>
                                @if ($sudahDiperiksa)
                                    <span class="badge bg-green-100 text-green-700 border-0">
                                        Selesai diperiksa
                                    </span>
                                @else
                                    <span class="badge bg-amber-100 text-amber-700 border-0">
                                        Menunggu pemeriksaan
                                    </span>
                                @endif
                            </td>

                            <td>
                                @if ($sudahDiperiksa)
                                    <a href="{{ route('pasien.riwayat.show', $item->id) }}"
                                        class="btn btn-sm text-white border-0 bg-blue-600 hover:bg-blue-700">
                                        Detail
                                    </a>
                                @else
                                    <span class="text-xs text-slate-400">
                                        Belum tersedia
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8"
                                class="text-center py-12 text-slate-400">
                                Belum ada riwayat pendaftaran.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4">
            {{ $riwayat->links() }}
        </div>
    </div>

</x-layouts.app>

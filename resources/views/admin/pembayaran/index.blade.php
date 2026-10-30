<x-layouts.app title="Verifikasi Pembayaran">
    <div class="py-8">
        <h2 class="text-2xl font-bold text-slate-800 mb-8">Verifikasi Pembayaran Pasien</h2>

        <div class="card bg-base-100 shadow-xl border border-slate-200 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 uppercase text-xs tracking-widest">
                            <th class="py-5 px-6">Pasien</th>
                            <th class="py-5 px-6">Dokter/Poli</th>
                            <th class="py-5 px-6">Tanggal</th>
                            <th class="py-5 px-6 text-right">Biaya</th>
                            <th class="py-5 px-6 text-center">Bukti Bayar</th>
                            <th class="py-5 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($pemeriksaans as $periksa)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-5 px-6">
                                    <div class="font-bold text-slate-800">{{ $periksa->daftarPoli?->pasien?->nama ?? '-' }}</div>
                                    <div class="text-xs text-slate-400">No. RM: {{ $periksa->daftarPoli?->pasien?->no_rm ?? '-' }}</div>
                                </td>
                                <td class="py-5 px-6">
                                    <div class="font-semibold text-slate-700">{{ $periksa->daftarPoli?->jadwalPeriksa?->dokter?->nama ?? '-' }}</div>
                                    <div class="text-xs text-slate-400">{{ $periksa->daftarPoli?->jadwalPeriksa?->dokter?->poli?->nama_poli ?? '-' }}</div>
                                </td>
                                <td class="py-5 px-6 text-sm">
                                    {{ \Carbon\Carbon::parse($periksa->tgl_periksa)->format('d/m/Y') }}
                                </td>
                                <td class="py-5 px-6 text-right font-black text-indigo-600">
                                    Rp {{ number_format($periksa->biaya_periksa, 0, ',', '.') }}
                                </td>
                                <td class="py-5 px-6 text-center">
                                    @if($periksa->bukti_bayar)
                                        <a href="{{ asset('storage/' . $periksa->bukti_bayar) }}" target="_blank" class="btn btn-ghost btn-xs text-primary">
                                            <i class="fas fa-image mr-1"></i> Lihat
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-300 italic">Tidak ada</span>
                                    @endif
                                </td>
                                <td class="py-5 px-6 text-center">
                                    <form action="{{ route('admin.pembayaran.verify', $periksa->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm rounded-xl font-bold text-white shadow-lg shadow-success/20">
                                            Verifikasi (Lunas)
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-20 text-center text-slate-400 italic">
                                    Tidak ada pembayaran yang menunggu verifikasi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>

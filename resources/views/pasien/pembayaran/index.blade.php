<x-layouts.app title="Pembayaran Saya">
    <div class="py-8">
        <h2 class="text-2xl font-bold text-slate-800 mb-8">Riwayat Pemeriksaan & Pembayaran</h2>

        <div class="grid grid-cols-1 gap-6">
            @forelse($pemeriksaans as $periksa)
                <div class="card bg-base-100 shadow-xl border border-slate-200 rounded-2xl overflow-hidden">
                    <div class="card-body p-6">
                        <div class="flex flex-col md:flex-row justify-between gap-6">
                            <div class="flex-1 space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="badge badge-lg bg-indigo-50 text-indigo-600 border-none font-bold">
                                        {{ \Carbon\Carbon::parse($periksa->tgl_periksa)->format('d F Y') }}
                                    </span>
                                    <div class="badge badge-outline 
                                        {{ $periksa->status_pembayaran == 'terbayar' ? 'badge-success' : ($periksa->status_pembayaran == 'verifikasi' ? 'badge-info' : 'badge-warning') }}">
                                        {{ strtoupper($periksa->status_pembayaran) }}
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <p class="text-slate-400 font-medium">Poli</p>
                                        <p class="font-bold text-slate-800">{{ $periksa->daftarPoli->jadwalPeriksa->dokter->poli->nama_poli ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-slate-400 font-medium">Dokter</p>
                                        <p class="font-bold text-slate-800">{{ $periksa->daftarPoli->jadwalPeriksa->dokter->nama ?? '-' }}</p>
                                    </div>
                                    <div class="col-span-full">
                                        <p class="text-slate-400 font-medium">Catatan Dokter</p>
                                        <p class="text-slate-700 italic">{{ $periksa->catatan ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="w-full md:w-64 flex flex-col justify-center items-center bg-slate-50 rounded-xl p-6 border border-slate-100">
                                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mb-1">Total Biaya</p>
                                <p class="text-2xl font-black text-[#2d4499]">Rp {{ number_format($periksa->biaya_periksa, 0, ',', '.') }}</p>
                                
                                @if($periksa->status_pembayaran == 'menunggu pembayaran')
                                    <form action="{{ route('pasien.pembayaran.upload', $periksa->id) }}" method="POST" enctype="multipart/form-data" class="mt-4 w-full">
                                        @csrf
                                        <div class="form-control w-full">
                                            <label class="label p-0 mb-1">
                                                <span class="label-text-alt font-bold">Upload Bukti Bayar</span>
                                            </label>
                                            <input type="file" name="bukti_bayar" class="file-input file-input-bordered file-input-primary file-input-sm w-full rounded-lg" required />
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm btn-block mt-3 rounded-lg font-bold">Unggah Bukti</button>
                                    </form>
                                @elseif($periksa->status_pembayaran == 'verifikasi')
                                    <div class="mt-4 text-center">
                                        <span class="text-xs text-info font-bold animate-pulse">Menunggu Verifikasi Admin</span>
                                    </div>
                                @else
                                    <div class="mt-4 text-center text-success flex items-center gap-2">
                                        <i class="fas fa-check-circle"></i>
                                        <span class="text-sm font-bold">LUNAS</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-20 text-center bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200">
                    <i class="fas fa-file-invoice-dollar text-4xl text-slate-200 mb-4"></i>
                    <p class="text-slate-500 italic">Belum ada riwayat pemeriksaan.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-layouts.app>

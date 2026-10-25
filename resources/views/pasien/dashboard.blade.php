<x-layouts.app title="Dashboard Pasien">

    <div x-data="queueMonitor()">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-extrabold text-slate-800">
                    Dashboard Pasien
                </h2>
                <p class="text-slate-500 text-sm mt-1">
                    Selamat datang kembali, {{ auth()->user()->nama }}
                </p>
            </div>
        </div>

        {{-- Active Queue Banner --}}
        @if ($antrianAktif)
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-blue-600 to-indigo-700 p-8 mb-10 shadow-xl text-white">
                <div class="relative flex flex-col md:flex-row items-center justify-between gap-8">
                    <div class="flex-1 space-y-4">
                        <h3 class="text-sm font-bold uppercase tracking-widest opacity-90">Antrian Aktif Anda</h3>
                        
                        <div class="space-y-3">
                            <div>
                                <p class="text-blue-100 text-xs font-medium opacity-80">Poliklinik</p>
                                <p class="text-2xl font-bold">{{ $antrianAktif->jadwalPeriksa->dokter->poli->nama_poli ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-blue-100 text-xs font-medium opacity-80">Dokter</p>
                                <p class="text-xl font-semibold">{{ $antrianAktif->jadwalPeriksa->dokter->nama ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-blue-100 text-xs font-medium opacity-80">Jadwal Periksa</p>
                                <p class="text-lg">
                                    {{ $antrianAktif->jadwalPeriksa->hari }} 
                                    ({{ \Carbon\Carbon::parse($antrianAktif->jadwalPeriksa->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($antrianAktif->jadwalPeriksa->jam_selesai)->format('H:i') }})
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-6">
                        {{-- Patient's Queue Number --}}
                        <div class="w-32 h-32 flex flex-col items-center justify-center rounded-2xl bg-white/10 backdrop-blur-md border border-white/20">
                            <p class="text-[10px] font-bold text-blue-100 uppercase tracking-widest mb-1">Nomor Anda</p>
                            <p class="text-5xl font-black">{{ $antrianAktif->no_antrian }}</p>
                        </div>

                        {{-- Currently Served --}}
                        <div class="w-36 h-36 flex flex-col items-center justify-center rounded-2xl bg-white shadow-2xl text-blue-700">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">SEDANG DILAYANI</p>
                            <p class="text-6xl font-black"
                                x-text="liveUpdates[{{ $antrianAktif->id_jadwal }}] ?? {{ $antrianAktif->antrian_sekarang }}">
                                {{ $antrianAktif->antrian_sekarang }}
                            </p>
                            <div class="mt-2 flex items-center gap-1 text-[8px] font-bold text-blue-600 uppercase">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                                </span>
                                Live Update
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="rounded-3xl border-2 border-dashed border-slate-200 p-12 text-center mb-10 bg-slate-50/50">
                <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                    <i class="fas fa-inbox text-3xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Tidak Ada Antrian Aktif</h3>
                <p class="text-slate-500 max-w-xs mx-auto mt-2">
                    Anda belum terdaftar di poliklinik manapun untuk hari ini.
                </p>
                <a href="{{ route('pasien.daftar') }}" class="btn btn-primary btn-md rounded-xl mt-6 px-8 font-bold border-none bg-[#2d4499] hover:bg-[#1e2d6b]">
                    Daftar Sekarang
                </a>
            </div>
        @endif

        {{-- Schedule Table Section --}}
        <div class="card bg-white shadow-xl shadow-slate-200/50 rounded-3xl border border-slate-100 overflow-hidden">
            <div class="p-8 border-b border-slate-100 bg-slate-50/30 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Daftar Jadwal Poliklinik</h3>
                    <p class="text-sm text-slate-500 mt-0.5">Pantau antrian semua poli secara real-time</p>
                </div>
                <div class="hidden sm:block">
                    <span class="badge badge-lg bg-indigo-50 text-indigo-600 border-none font-bold py-4 px-6 rounded-xl">
                        {{ now()->translatedFormat('l, d F Y') }}
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr class="text-slate-400 text-xs uppercase tracking-widest border-b border-slate-100">
                            <th class="bg-transparent px-8 py-5">No</th>
                            <th class="bg-transparent px-6 py-5">Poliklinik</th>
                            <th class="bg-transparent px-6 py-5">Dokter</th>
                            <th class="bg-transparent px-6 py-5">Hari</th>
                            <th class="bg-transparent px-6 py-5">Jam Periksa</th>
                            <th class="bg-transparent px-8 py-5 text-center">Sedang Dilayani</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($jadwals as $index => $jadwal)
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="px-8 py-5 text-slate-400 font-medium">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-5">
                                    <div class="font-bold text-slate-800">{{ $jadwal->dokter->poli->nama_poli ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="font-semibold text-slate-700">{{ $jadwal->dokter->nama ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg bg-slate-100 text-slate-600 text-xs font-bold uppercase">
                                        {{ $jadwal->hari }}
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-2 text-slate-500 font-medium">
                                        <i class="far fa-clock text-xs text-indigo-400"></i>
                                        {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 font-black text-xl group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300"
                                        x-text="liveUpdates[{{ $jadwal->id }}] ?? {{ $jadwal->antrian_sekarang }}">
                                        {{ $jadwal->antrian_sekarang }}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-20 text-slate-400">
                                    <i class="fas fa-calendar-times text-4xl mb-4 block opacity-20"></i>
                                    Belum ada jadwal praktik yang tersedia.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@push('scripts')
<script>
    function queueMonitor() {
        const jadwalIds = @json($jadwals->pluck('id')->values());

        return {
            liveUpdates: {},
            pollTimer: null,
            subscribedChannels: [],

            init() {
                // Ambil data awal dari server.
                this.fetchUpdates();

                // Polling menjadi cadangan jika WebSocket terputus.
                this.pollTimer = window.setInterval(() => {
                    this.fetchUpdates();
                }, 5000);

                // Dengarkan event Reverb untuk setiap jadwal.
                if (window.Echo) {
                    jadwalIds.forEach((jadwalId) => {
                        const channelName = `antrean.${jadwalId}`;

                        window.Echo
                            .channel(channelName)
                            .listen('.antrean.updated', (event) => {
                                this.liveUpdates[event.jadwal_id] =
                                    Number(event.antrian_sekarang ?? 0);

                                console.log(
                                    'Update antrean diterima:',
                                    event
                                );
                            });

                        this.subscribedChannels.push(channelName);
                    });

                    console.log('Listener antrean Reverb aktif.');
                } else {
                    console.warn(
                        'Laravel Echo belum tersedia. Polling tetap digunakan.'
                    );
                }
            },

            async fetchUpdates() {
                try {
                    const response = await fetch(
                        '{{ route('pasien.live-update') }}',
                        {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        }
                    );

                    if (response.ok) {
                        const data = await response.json();

                        this.liveUpdates = {
                            ...this.liveUpdates,
                            ...data
                        };
                    }
                } catch (error) {
                    console.error(
                        'Gagal mengambil update antrean:',
                        error
                    );
                }
            },

            destroy() {
                if (this.pollTimer) {
                    window.clearInterval(this.pollTimer);
                }

                if (window.Echo) {
                    this.subscribedChannels.forEach((channelName) => {
                        window.Echo.leave(channelName);
                    });
                }
            }
        };
    }
</script>
@endpush

</x-layouts.app>

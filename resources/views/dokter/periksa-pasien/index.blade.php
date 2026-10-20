<x-layouts.app title="Periksa Pasien">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">
            Periksa Pasien
        </h2>
    </div>

    {{-- Alert Flash Message --}}
    @if (session('message'))
    <div class="alert alert-{{ session('type', 'success') }} alert-dismissible mb-4 rounded-xl shadow-sm" role="alert">
        <i class="fas fa-circle-check"></i>
        <span>{{ session('message') }}</span>
    </div>
    @endif

    {{-- Card --}}
    <div class="card bg-base-100 shadow-md rounded-2 border">
        <div class="card-body p-0">

            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">

                    {{-- Head --}}
                    <thead class="bg-slate-100 text-slate-500 text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">Nama Pasien</th>
                            <th class="px-6 py-4">Keluhan</th>
                            <th class="px-6 py-4">Nomor Antrian</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>

                    {{-- Body --}}
                    <tbody>
                        @forelse ($daftarPasien as $daftar)
                        <tr class="hover:bg-slate-50 transition">

                            <td class="px-6 py-4 text-slate-500">
                                {{ $daftar->id }}
                            </td>

                            <td class="px-6 py-4 font-semibold text-slate-800">
                                {{ $daftar->pasien->nama ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-slate-500">
                                {{ $daftar->keluhan ?: '-' }}
                            </td>

                            <td class="px-6 py-4 text-slate-500">
                                {{ $daftar->no_antrian }}
                            </td>

                            <td class="px-6 py-4 text-right">
                                @if ($daftar->periksas->isNotEmpty())

                                <span
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-100 text-emerald-700 text-sm font-semibold">
                                    <i class="fas fa-circle-check"></i>
                                    Sudah Diperiksa
                                </span>

                                @else

                                <a href="{{ route('periksa-pasien.create', $daftar->id) }}" class="btn btn-sm bg-[#2d4499] hover:bg-[#1e2d6b] 
                                          text-white border-none rounded-lg px-4">
                                    <i class="fas fa-stethoscope"></i>
                                    Periksa
                                </a>

                                @endif
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-14 text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i class="fas fa-inbox text-3xl"></i>
                                    <span>Belum ada pasien yang menunggu diperiksa</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

    <script>
        setTimeout(() => {
            const alert = document.querySelector('.alert');
            if (alert) {
                alert.classList.remove('show');
                alert.classList.add('fade');
                setTimeout(() => alert.remove(), 500);
            }
        }, 2000);
    </script>

</x-layouts.app>

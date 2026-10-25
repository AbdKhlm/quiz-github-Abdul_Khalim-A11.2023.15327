<x-layouts.app title="Pendaftaran Periksa">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">
            Pendaftaran Periksa
        </h2>
    </div>

    {{-- Alert Flash Message --}}
    @if (session('message'))
    <div class="alert alert-{{ session('type', 'success') }} mb-4 rounded-xl shadow-sm" role="alert">
        <i class="fas fa-circle-check"></i>
        <span>{{ session('message') }}</span>
    </div>
    @endif

    {{-- Alert Error --}}
    @if ($errors->any())
    <div class="alert alert-error mb-4 rounded-xl shadow-sm">
        <i class="fas fa-circle-xmark"></i>
        <span>{{ $errors->first() }}</span>
    </div>
    @endif

    {{-- Data Pasien --}}
    <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200 mb-6">
        <div class="card-body p-8">

            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-[#2d4499] text-white flex items-center justify-center shrink-0">
                    <i class="fas fa-user"></i>
                </div>

                <div>
                    <div class="text-xs uppercase tracking-wider text-slate-400">
                        Pasien
                    </div>
                    <div class="font-bold text-slate-800">
                        {{ $user->nama }}
                    </div>
                    <div class="text-sm text-slate-500">
                        No. RM: {{ $user->no_rm ?: '-' }}
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Form Pendaftaran --}}
    <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200">
        <div class="card-body p-8">

            <form action="{{ route('pasien.daftar.submit') }}" method="POST" onsubmit="return validasiForm()">
                @csrf

                <input type="hidden" name="id_pasien" value="{{ $user->id }}">

                {{-- Pilih Poli --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">
                        Pilih Poli <span class="text-red-500">*</span>
                    </label>

                    <select name="id_poli" id="id_poli" onchange="filterJadwal()" class="w-full px-4 py-2 border-2 rounded-lg 
                                   focus:border-primary focus:outline-none
                                   @error('id_poli') border-red-500 @enderror" required>
                        <option value="">-- Pilih Poli --</option>
                        @foreach ($polis as $poli)
                        <option value="{{ $poli->id }}" {{ old('id_poli') == $poli->id ? 'selected' : '' }}>
                            {{ $poli->nama_poli }}
                        </option>
                        @endforeach
                    </select>

                    @error('id_poli')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Pilih Jadwal Dokter --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">
                        Pilih Jadwal Dokter <span class="text-red-500">*</span>
                    </label>

                    <select name="id_jadwal" id="id_jadwal" class="w-full px-4 py-2 border-2 rounded-lg 
                                   focus:border-primary focus:outline-none
                                   @error('id_jadwal') border-red-500 @enderror" required disabled>
                        <option value="">-- Pilih Poli terlebih dahulu --</option>
                        @foreach ($jadwals as $jadwal)
                        <option value="{{ $jadwal->id }}" data-poli="{{ $jadwal->dokter->id_poli ?? '' }}"
                            {{ old('id_jadwal') == $jadwal->id ? 'selected' : '' }}>
                            {{ $jadwal->dokter->nama ?? '-' }} - {{ $jadwal->hari }}
                            ({{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}
                            - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }})
                        </option>
                        @endforeach
                    </select>

                    <p id="info_jadwal" class="text-xs text-slate-400 mt-1">
                        Pilih poli untuk menampilkan jadwal dokter yang tersedia.
                    </p>

                    @error('id_jadwal')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Keluhan --}}
                <div class="mb-8">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">
                        Keluhan
                    </label>

                    <textarea name="keluhan" rows="4" placeholder="Tuliskan keluhan yang dirasakan..."
                        class="w-full px-4 py-2 border-2 rounded-lg 
                                     focus:border-primary focus:outline-none
                                     @error('keluhan') border-red-500 @enderror">{{ old('keluhan') }}</textarea>

                    @error('keluhan')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Buttons --}}
                <div class="flex gap-3">
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary 
                               hover:bg-primary/90 text-white 
                               font-semibold text-sm transition">
                        <i class="fas fa-clipboard-check mr-1"></i>
                        Daftar
                    </button>
                </div>

            </form>

        </div>
    </div>

    <script>
        function filterJadwal() {
            const poli = document.getElementById('id_poli').value;
            const select = document.getElementById('id_jadwal');
            const info = document.getElementById('info_jadwal');
            const options = select.querySelectorAll('option[data-poli]');

            let jumlahTersedia = 0;

            options.forEach(option => {
                const sesuai = poli !== '' && option.dataset.poli === poli;

                if (sesuai) {
                    option.style.display = 'block';
                    option.disabled = false;
                    jumlahTersedia++;
                } else {
                    option.style.display = 'none';
                    option.disabled = true;
                }
            });

            if (poli === '') {
                select.disabled = true;
                select.value = '';
                info.textContent = 'Pilih poli untuk menampilkan jadwal dokter yang tersedia.';
                return;
            }

            select.disabled = false; // Selalu aktifkan jika poli dipilih agar bisa melihat pesan "Belum ada jadwal"

            // kosongkan pilihan jika jadwal yang dipilih tidak termasuk poli ini
            const terpilih = select.options[select.selectedIndex];

            if (terpilih && terpilih.disabled && terpilih.value !== '') {
                select.value = '';
            }

            if (jumlahTersedia === 0) {
                info.textContent = 'Belum ada jadwal dokter untuk poli ini.';
                info.className = 'text-xs text-red-500 mt-1';
            } else {
                info.textContent = jumlahTersedia + ' jadwal dokter tersedia.';
                info.className = 'text-xs text-slate-400 mt-1';
            }
        }

        function validasiForm() {
            if (document.getElementById('id_jadwal').value === '') {
                alert('Silakan pilih jadwal dokter terlebih dahulu.');
                return false;
            }

            return true;
        }

        filterJadwal();
    </script>

</x-layouts.app>

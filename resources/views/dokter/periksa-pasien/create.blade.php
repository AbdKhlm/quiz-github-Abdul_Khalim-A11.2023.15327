<x-layouts.app title="Periksa Pasien">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('periksa-pasien.index') }}" class="flex items-center justify-center w-9 h-9 
                  rounded-lg bg-slate-100 hover:bg-slate-200 
                  text-slate-600 transition">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>

        <h2 class="text-2xl font-bold text-slate-800">
            Periksa Pasien
        </h2>
    </div>

    {{-- Alert Error --}}
    @if ($errors->any())
    <div class="alert alert-error mb-4 rounded-xl shadow-sm">
        <i class="fas fa-circle-xmark"></i>
        <span>{{ $errors->first() }}</span>
    </div>
    @endif

    <form action="{{ route('periksa-pasien.store') }}" method="POST" onsubmit="return validasiForm()">
        @csrf

        <input type="hidden" name="id_daftar_poli" value="{{ $id }}">
        <input type="hidden" name="obat_json" id="obat_json">
        <input type="hidden" name="biaya_periksa" id="biaya_periksa" value="0">

        {{-- Pilih Obat --}}
        <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200 mb-6">
            <div class="card-body p-8">

                <label class="block text-sm font-semibold text-slate-700 mb-1">
                    Pilih Obat
                </label>

                <div class="flex flex-col sm:flex-row gap-3">

                    <select id="pilih_obat" class="w-full px-4 py-2 border-2 rounded-lg 
                                   focus:border-primary focus:outline-none">
                        @forelse ($obats as $obat)
                        <option value="{{ $obat->id }}" data-nama="{{ $obat->nama_obat }}"
                            data-kemasan="{{ $obat->kemasan }}" data-harga="{{ $obat->harga }}">
                            {{ $obat->nama_obat }} @if ($obat->kemasan) ({{ $obat->kemasan }}) @endif
                            - Rp {{ number_format($obat->harga, 0, ',', '.') }}
                        </option>
                        @empty
                        <option value="">Belum ada data obat</option>
                        @endforelse
                    </select>

                    <button type="button" onclick="tambahObat()" class="px-6 py-2.5 rounded-lg bg-[#2d4499] 
                               hover:bg-[#1e2d6b] text-white 
                               font-semibold text-sm transition whitespace-nowrap">
                        <i class="fas fa-plus mr-1"></i>
                        Tambah
                    </button>

                </div>

            </div>
        </div>

        {{-- Obat Terpilih --}}
        <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200 mb-6">
            <div class="card-body p-0">

                <div class="px-8 pt-6 pb-4">
                    <h3 class="font-bold text-slate-800">
                        Obat Terpilih
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="table w-full">

                        <thead class="bg-slate-100 text-slate-500 text-xs uppercase tracking-wider">
                            <tr>
                                <th class="px-8 py-4">Nama Obat</th>
                                <th class="px-6 py-4">Kemasan</th>
                                <th class="px-6 py-4">Harga</th>
                                <th class="px-8 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>

                        <tbody id="obat_terpilih_body">
                            <tr>
                                <td colspan="4" class="text-center py-10 text-slate-400">
                                    Belum ada obat yang dipilih
                                </td>
                            </tr>
                        </tbody>

                    </table>
                </div>

            </div>
        </div>

        {{-- Total Harga --}}
        <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200 mb-6">
            <div class="card-body p-8">

                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm font-semibold text-slate-700">
                            Total Harga
                        </div>
                        <div class="text-xs text-slate-400">
                            Dihitung otomatis dari obat yang dipilih
                        </div>
                    </div>

                    <div id="total_harga" class="text-2xl font-bold text-slate-800">
                        Rp 0
                    </div>
                </div>

            </div>
        </div>

        {{-- Catatan --}}
        <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200 mb-6">
            <div class="card-body p-8">

                <label class="block text-sm font-semibold text-slate-700 mb-1">
                    Catatan
                </label>

                <textarea name="catatan" rows="4" placeholder="Tuliskan catatan atau hasil pemeriksaan pasien..."
                    class="w-full px-4 py-2 border-2 rounded-lg 
                                 focus:border-primary focus:outline-none">{{ old('catatan') }}</textarea>

                @error('catatan')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror

            </div>
        </div>

        {{-- Buttons --}}
        <div class="flex gap-3">
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary 
                               hover:bg-primary/90 text-white 
                               font-semibold text-sm transition">
                <i class="fas fa-save mr-1"></i>
                Simpan
            </button>

            <a href="{{ route('periksa-pasien.index') }}" class="px-6 py-2.5 rounded-xl bg-slate-100 
                              hover:bg-slate-200 text-slate-600 
                              font-semibold text-sm transition">
                Batal
            </a>
        </div>

    </form>

    <script>
        const obatTerpilih = [];

        function formatRupiah(angka) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka);
        }

        function tambahObat() {
            const select = document.getElementById('pilih_obat');
            const option = select.options[select.selectedIndex];

            if (!option || !option.value) {
                alert('Belum ada data obat yang bisa dipilih.');
                return;
            }

            if (obatTerpilih.some(obat => obat.id === option.value)) {
                alert('Obat ini sudah ada di daftar obat terpilih.');
                return;
            }

            obatTerpilih.push({
                id: option.value,
                nama: option.dataset.nama,
                kemasan: option.dataset.kemasan,
                harga: parseInt(option.dataset.harga, 10) || 0
            });

            renderObat();
        }

        function hapusObat(id) {
            const index = obatTerpilih.findIndex(obat => obat.id === id);

            if (index !== -1) {
                obatTerpilih.splice(index, 1);
                renderObat();
            }
        }

        function renderObat() {
            const body = document.getElementById('obat_terpilih_body');

            if (obatTerpilih.length === 0) {
                body.innerHTML =
                    '<tr><td colspan="4" class="text-center py-10 text-slate-400">Belum ada obat yang dipilih</td></tr>';
            } else {
                body.innerHTML = obatTerpilih.map(obat => `
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-8 py-4 font-semibold text-slate-800">${obat.nama}</td>
                        <td class="px-6 py-4 text-slate-500">${obat.kemasan ? obat.kemasan : '-'}</td>
                        <td class="px-6 py-4 text-slate-500">${formatRupiah(obat.harga)}</td>
                        <td class="px-8 py-4 text-right">
                            <button type="button" onclick="hapusObat('${obat.id}')" class="btn btn-sm bg-red-500 hover:bg-red-600 
                                       text-white border-none rounded-lg px-4">
                                <i class="fas fa-trash"></i>
                                Hapus
                            </button>
                        </td>
                    </tr>
                `).join('');
            }

            const total = obatTerpilih.reduce((jumlah, obat) => jumlah + obat.harga, 0);

            document.getElementById('total_harga').textContent = formatRupiah(total);
            document.getElementById('biaya_periksa').value = total;
            document.getElementById('obat_json').value = JSON.stringify(obatTerpilih.map(obat => obat.id));
        }

        function validasiForm() {
            if (obatTerpilih.length === 0) {
                alert('Pilih minimal satu obat terlebih dahulu.');
                return false;
            }

            return true;
        }

        renderObat();
    </script>

</x-layouts.app>

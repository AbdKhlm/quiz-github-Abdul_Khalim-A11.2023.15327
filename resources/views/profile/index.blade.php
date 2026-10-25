<x-layouts.app title="Profil Pengguna">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">
            Profil & Keamanan
        </h2>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Info Profil --}}
        <div class="card bg-base-100 shadow-md border border-slate-200">
            <div class="card-body">
                <h3 class="text-lg font-bold text-slate-800 mb-4">
                    Informasi Akun
                </h3>

                <div class="space-y-4">
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-slate-400 block mb-1">Nama Lengkap</label>
                        <p class="text-slate-700 font-medium">{{ $user->nama }}</p>
                    </div>

                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-slate-400 block mb-1">Email</label>
                        <p class="text-slate-700 font-medium">{{ $user->email }}</p>
                    </div>

                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-slate-400 block mb-1">Role</label>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider 
                            {{ $user->role === 'admin' ? 'bg-indigo-100 text-indigo-600' : '' }}
                            {{ $user->role === 'dokter' ? 'bg-purple-100 text-purple-600' : '' }}
                            {{ $user->role === 'pasien' ? 'bg-amber-100 text-amber-600' : '' }}">
                            {{ $user->role }}
                        </span>
                    </div>

                    @if($user->role === 'pasien')
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-slate-400 block mb-1">Nomor RM</label>
                        <p class="text-slate-700 font-medium">{{ $user->no_rm ?? '-' }}</p>
                    </div>
                    @endif

                    @if($user->role === 'dokter')
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-slate-400 block mb-1">Poli</label>
                        <p class="text-slate-700 font-medium">{{ $user->poli->nama_poli ?? '-' }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Form Ganti Password --}}
        <div class="lg:col-span-2 card bg-base-100 shadow-md border border-slate-200">
            <div class="card-body">
                <h3 class="text-lg font-bold text-slate-800 mb-4">
                    Ganti Password
                </h3>

                <form action="{{ route('profile.password.update') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-6">
                        {{-- Password Lama --}}
                        <div class="form-control w-full">
                            <label class="label pt-0">
                                <span class="label-text font-semibold text-slate-700">Password Lama <span class="text-red-500">*</span></span>
                            </label>
                            <input type="password" name="current_password" 
                                class="input input-bordered w-full @error('current_password') input-error @enderror" 
                                placeholder="Masukkan password saat ini" required>
                            @error('current_password')
                                <label class="label py-1">
                                    <span class="label-text-alt text-red-500 font-medium">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        {{-- Password Baru --}}
                        <div class="form-control w-full">
                            <label class="label pt-0">
                                <span class="label-text font-semibold text-slate-700">Password Baru <span class="text-red-500">*</span></span>
                            </label>
                            <input type="password" name="password" 
                                class="input input-bordered w-full @error('password') input-error @enderror" 
                                placeholder="Minimal 8 karakter" required>
                            @error('password')
                                <label class="label py-1">
                                    <span class="label-text-alt text-red-500 font-medium">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        {{-- Konfirmasi Password Baru --}}
                        <div class="form-control w-full">
                            <label class="label pt-0">
                                <span class="label-text font-semibold text-slate-700">Konfirmasi Password Baru <span class="text-red-500">*</span></span>
                            </label>
                            <input type="password" name="password_confirmation" 
                                class="input input-bordered w-full" 
                                placeholder="Ulangi password baru" required>
                        </div>

                        <div class="flex justify-end gap-3 pt-2">
                            <a href="{{ route($user->role . '.dashboard') }}" class="btn btn-ghost px-8">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-primary px-8">
                                <i class="fas fa-key mr-2"></i>
                                Ganti Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-layouts.app>

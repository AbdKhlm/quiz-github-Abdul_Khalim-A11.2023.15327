<header class="bg-base-100 border-b border-base-300 h-16 flex items-center px-6 gap-4 sticky top-0 z-30 shadow-sm">

    {{-- Mobile Hamburger --}}
    <button onclick="toggleSidebar()" class="btn btn-square btn-ghost lg:hidden">
        <i data-lucide="menu" class="w-5 h-5"></i>
    </button>

    {{-- Breadcrumb --}}
    <div class="flex-1">
        <div class="flex items-center gap-2 text-sm">
            <span class="text-base-content/50">Poliklinik</span>
            <i data-lucide="chevron-right" class="w-4 h-4 text-base-content/30"></i>
            <span class="font-semibold text-base-content">
                {{ $title ?? 'Dashboard' }}
            </span>
        </div>
    </div>

    {{-- Fullscreen --}}
    <button onclick="toggleFullscreen()" class="btn btn-square btn-ghost">
        <i id="fsIcon" class="fas fa-expand w-5 h-5"></i>
    </button>

    {{-- Notification Bell --}}
    <div class="dropdown dropdown-end">
        <label tabindex="0" class="btn btn-ghost btn-circle">
            <div class="indicator">
                <i class="fas fa-bell w-5 h-5"></i>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="badge badge-xs badge-primary indicator-item"></span>
                @endif
            </div>
        </label>
        <div tabindex="0" class="mt-3 z-[1] card card-compact dropdown-content w-80 bg-base-100 shadow-xl border border-base-200">
            <div class="card-body">
                <div class="flex items-center justify-between border-b pb-2 mb-2">
                    <h3 class="font-bold text-md">Notifikasi</h3>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs text-primary hover:underline">Tandai semua dibaca</button>
                        </form>
                    @endif
                </div>
                
                <div class="max-h-64 overflow-y-auto space-y-2">
                    @forelse(auth()->user()->notifications()->latest()->take(5)->get() as $notification)
                        <div class="p-2 rounded-lg {{ $notification->read_at ? 'bg-base-100' : 'bg-base-200 border-l-4 border-primary' }}">
                            <div class="flex justify-between items-start">
                                <p class="text-xs leading-relaxed {{ $notification->read_at ? 'text-base-content/70' : 'text-base-content font-medium' }}">
                                    {{ $notification->data['message'] }}
                                </p>
                                @if(!$notification->read_at)
                                    <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-[10px] text-primary hover:underline ml-2 shrink-0">Baca</button>
                                    </form>
                                @endif
                            </div>
                            <span class="text-[10px] opacity-50 mt-1 block">
                                {{ $notification->created_at->diffForHumans() }}
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <p class="text-sm text-base-content/50 italic">Belum ada notifikasi.</p>
                        </div>
                    @endforelse
                </div>

                @if(auth()->user()->notifications->count() > 5)
                    <div class="card-actions mt-2 pt-2 border-t">
                        <a href="{{ route('notifications.index') }}" class="btn btn-primary btn-sm btn-block">Lihat Semua</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- User Info --}}
    <div class="flex items-center gap-3">

        <div class="text-right hidden sm:block">
            <div class="text-sm font-semibold leading-tight">
                {{ auth()->user()->nama ?? 'Pengguna' }}
            </div>
            <div class="text-xs text-base-content/50 leading-tight">
                {{ auth()->user()->role ?? 'Admin Sistem' }}
            </div>
        </div>

        <div class="avatar">
            <div class="w-10 h-10 rounded-full bg-primary text-primary-content flex items-center justify-center">
                <span class="text-sm font-semibold leading-none">
                    {{ strtoupper(substr(auth()->user()->nama ?? 'U', 0, 1)) }}
                </span>
            </div>
        </div>
    </div>

</header>

<script>
    function toggleFullscreen() {
    const icon = document.getElementById('fsIcon');

    if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen();
        icon.classList.remove('fa-expand');
        icon.classList.add('fa-compress');
    } else {
        document.exitFullscreen();
        icon.classList.remove('fa-compress');
        icon.classList.add('fa-expand');
    }
}
</script>
<x-layouts.app title="Semua Notifikasi">
    <div class="max-w-4xl mx-auto py-8">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-slate-800">Semua Notifikasi</h2>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline btn-primary btn-sm rounded-xl">
                        Tandai semua sudah dibaca
                    </button>
                </form>
            @endif
        </div>

        <div class="card bg-base-100 shadow-xl border border-base-200 overflow-hidden rounded-2xl">
            <div class="divide-y divide-base-200">
                @forelse($notifications as $notification)
                    <div class="p-6 flex items-start gap-4 transition-colors {{ $notification->read_at ? 'bg-base-100' : 'bg-base-200/50' }}">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 
                            {{ $notification->data['type'] == 'success' ? 'bg-success/20 text-success' : 'bg-info/20 text-info' }}">
                            <i class="fas {{ $notification->data['type'] == 'success' ? 'fa-check-circle' : 'fa-info-circle' }}"></i>
                        </div>
                        
                        <div class="flex-1">
                            <div class="flex justify-between items-start mb-1">
                                <p class="text-sm font-semibold {{ $notification->read_at ? 'text-slate-600' : 'text-slate-900' }}">
                                    {{ $notification->data['message'] }}
                                </p>
                                <span class="text-xs text-slate-400">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                            
                            @if(!$notification->read_at)
                                <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" class="mt-2">
                                    @csrf
                                    <button type="submit" class="text-xs text-primary font-bold hover:underline">
                                        Tandai sudah dibaca
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-20 text-center">
                        <i class="fas fa-bell-slash text-4xl text-slate-200 mb-4"></i>
                        <p class="text-slate-500 italic">Belum ada notifikasi untuk ditampilkan.</p>
                    </div>
                @endforelse
            </div>
            
            @if($notifications->hasPages())
                <div class="p-4 bg-slate-50 border-t border-slate-100">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>

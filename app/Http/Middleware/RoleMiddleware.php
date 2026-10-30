<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Closure;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        $user = Auth::user();

        if ($user->role !== $role) {
            // Jika role tidak sesuai, arahkan ke dashboard masing-masing role
            $dashboardRoute = match($user->role) {
                'admin' => 'admin.dashboard',
                'dokter' => 'dokter.dashboard',
                'pasien' => 'pasien.dashboard',
                default => 'login',
            };

            return redirect()->route($dashboardRoute)->with('error', 'Anda tidak memiliki akses ke halaman tersebut. Anda telah dialihkan ke dashboard sesuai role Anda.');
        }

        return $next($request);
    }
}
<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\RoleHelper;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ActiveRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next, $roleName): Response
    // {
    //     $active = auth()->user()->activeRole?->name;

    //     if ($active !== $roleName) {
    //         abort(403, 'Akses ditolak. Role aktif tidak sesuai.');
    //     }

    //     return $next($request);
    // }

    public function handle($request, Closure $next, $roleName)
    {
        $user = auth()->user();

        // Pastikan sudah login
        if (!$user) {
            abort(403, 'Unauthorized.');
        }

        // SUPER ADMIN SELALU BOLEH MASUK
        if ($user->hasRole('Super-Admin')) {
            return $next($request);
        }

        // Tidak punya role aktif → tolak
        if (!$user->activeRole) {
            abort(403, 'Tidak ada active role yang dipilih.');
        }

        // Role aktif tidak sesuai dengan role yang diminta middleware
        if (strtolower($user->activeRole->name) !== strtolower($roleName)) {
            abort(403, 'Anda tidak memiliki role aktif yang sesuai.');
        }
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!in_array(auth()->user()->role, $roles)) {
            abort(403, 'Akses tidak diizinkan.');
        }

        if (!auth()->user()->is_active) {
            auth()->logout();
            return redirect()->route('login')
                ->withErrors(['nik' => 'Akun Anda telah dinonaktifkan. Hubungi Administrator.']);
        }

        return $next($request);
    }
}

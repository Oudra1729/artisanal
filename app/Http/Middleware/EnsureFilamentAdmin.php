<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFilamentAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ! $user->isAdmin()) {
            auth()->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('filament.admin.auth.login')
                ->withErrors([
                    'email' => 'Accès réservé aux administrateurs. Utilisez admin@tissu.ma pour vous connecter.',
                ]);
        }

        return $next($request);
    }
}

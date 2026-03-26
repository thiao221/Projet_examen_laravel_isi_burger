<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Si l'utilisateur n'est pas connecté → page login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Si le rôle de l'utilisateur ne correspond pas → erreur 403
        if (auth()->user()->role !== $role) {
            abort(403, 'Accès non autorisé. Vous n\'avez pas les droits nécessaires.');
        }

        // Tout est bon, on laisse passer la requête
        return $next($request);
    }
}

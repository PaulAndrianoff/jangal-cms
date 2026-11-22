<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfInstalled
{
    public function handle(Request $request, Closure $next)
    {
        // Si le fichier de verrouillage existe, on redirige vers l'accueil
        if (file_exists(storage_path('installed.lock'))) {
            return redirect('/');
        }
        return $next($request);
    }
}
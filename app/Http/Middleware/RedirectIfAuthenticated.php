<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            // Usuário autenticado, agora verifique se a sessão expirou
            if (session('last_activity') < now()->subMinutes(config('session.lifetime'))) {
                Auth::guard($guard)->logout();
                return redirect(route('login'))->with('session_expired', 'Sua sessão expirou. Por favor, faça login novamente.');
            }
        }

        return $next($request);
    }

}
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        if (!$this->auth->user()) {
            return redirect()->route('login.index')->with('session_expired', ' Você deve estar autenticado para acessar esta página!');
        }

        return parent::handle($request, $next, ...$guards);
    }
}

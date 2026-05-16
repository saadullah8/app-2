<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $allowed = false;
        $user_id = Auth::id();
        $role = Auth::user()->role->role;
        if ($role == 'Super Admin') {
            $allowed = true;
        }

        $route = $request->route()->getAction();
        $action = substr($route['uses'], strpos($route['uses'], "@") + 1);
        $controller = str_replace($route['namespace'] . '\\', '', $route['uses']);
        $controller = str_replace('@' . $action, '', $controller);

        switch ($controller) {
            case 'OrderController':
                if ($role == 'Admin') {
                    $allowed = true;
                }
                break;
        }

        if ($allowed) {
            return $next($request);
        } else {
            abort('401');
        }

    }
}

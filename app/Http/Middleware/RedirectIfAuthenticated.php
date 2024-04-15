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
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                /**
                 * @var \App\Models\User
                 */
                $user = Auth::guard($guard)->user();

                if($user->hasPermissionTo('view-admin-dashboard')){
                    return redirect()->route('admin.dashboard.index');
                }

                if($user->hasPermissionTo('view-agent-dashboard')){
                    return redirect()->route('agent.dashboard.index');
                }
            }
        }

        return $next($request);
    }
}

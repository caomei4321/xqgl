<?php

namespace App\Http\Middleware;

use Closure;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /*if (auth()->user()->hasPermissionTo('user.edit')) {
            dd(111);
        }*/
        $routeName  = $request->route()->getName();
        //dd($routeName);
        $user = auth()->user();
        try {
            if ($user->hasPermissionTo($routeName, 'admin')) {
                return $next($request);
            }
        } catch (\Exception $exception) {
            dd('没有权限');
        }

    }
}

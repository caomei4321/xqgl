<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

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
        $num = strripos($routeName, '.');

        $permission = str_replace('.', '\\', substr($routeName, 0, $num)) ;

        try {
            if (Auth::user()->can($permission) || Auth::user()->id == 1) {
                return $next($request);
            } else  {
                return redirect()->route('admin.counts.index');
            }
        } catch (\Exception $exception) {
            return response()->json(['err'=> '暂无权限']);
        }

    }
}

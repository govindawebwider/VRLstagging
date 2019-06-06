<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckRole
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
        dd($request);
        $type = $request->user()->type;
        if($type=="Artist"){
            return redirect('Dashboard');
        }
        elseif ($type=="User") {
            return redirect('profile');
        }
        elseif ($type=="Admin") {
            return redirect('admin_dashboard');
        }
        else{
            return redirect('/');
        }
    }
}

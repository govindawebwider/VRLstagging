<?php



namespace App\Http\Middleware;



use Closure;



class ActiveUserMiddleware

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
        

    	// if (\Auth::check()) {

    	// 	if (\Auth::user()->is_account_active == 0) {

     //            \Session::flush();

     //            \Auth::logout();

     //            return redirect('/login');

    	// 	} 

     //    }

       return $next($request);



   }

}


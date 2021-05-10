<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
            //if youre trying to go to dashboard without sessions
            if (!session()->has('LoggedUser') && ($request->path() != '/' && $request->path() !='/')){
                return redirect('/')->with('fail', 'You must be logged in');
            }
            //if youre logged in and trying to go to login page
            if(session()->has('LoggedUser') && ($request->path() == '/' || $request->path() == '/')){
                return back();
            }
            //tricks -> when you are logged out then you press the back button
            return $next($request) -> header('Cache-Control','no-cache, no-store, max-age=0, must-revalidate')
                                   -> header('Pragma','no-cache')
                                   -> header('Expires','Sat 01 Jan 1990 00:00:00 GMT');


    }
}

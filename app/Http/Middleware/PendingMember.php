<?php

namespace App\Http\Middleware;

use Closure;

class PendingMember
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
        if(\Auth::user()->status == 0){
            return redirect(route('profile.index'))->with('success', 'Pending proof of payment');
        }
        return $next($request);
    }
}

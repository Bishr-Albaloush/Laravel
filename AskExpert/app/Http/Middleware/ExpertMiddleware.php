<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Auth;
class ExpertMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    use GeneralTrait;
    public function handle(Request $request, Closure $next)
    {
        
        if(Auth::check()){

            if(!Auth::user()->expert){

                return $this->returnError('004','Access Denied as you are not Admin!');

            }
            else{
                return $next($request);
                
            }
        }else{
            return $this->returnError('001','Unauthenticated');
        }
       
    }
}

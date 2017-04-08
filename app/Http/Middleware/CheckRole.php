<?php

namespace Udoktor\Http\Middleware;

use Closure;
use Auth;
use Role;
use Udoktor\V_person;
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
        if (Auth::check()) {
            if(Auth::user()===null){
                Auth::logout();
                return redirect('login/inicio');
            }
            
            $actions=$request->route()->getAction();
            $roles=  isset($actions['roles'])? $actions['roles'] : null;

            if(Auth::user()->hasAnyRole($roles)|| !$roles){
                return $next($request);
            }
            
            $idPerson   = Auth::user()->personid;
            $dataPerson = V_person::find($idPerson);
            if (count($dataPerson) > 0) {
                if ($dataPerson->isserviceprovider == true) {
                    return redirect('prestadorServicios');
                } else if ($dataPerson->isclient == true) {
                    return redirect('cliente');
                }else if($dataPerson->isadmin==true){
                    return redirect('admin');
                } else {
                    return redirect('cliente');
                }
            }
            
            
        }else{
            Auth::logout();
            return redirect('login/inicio');
        }
    }
}

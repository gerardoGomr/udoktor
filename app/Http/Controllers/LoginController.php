<?php

namespace Udoktor\Http\Controllers;
use Illuminate\Http\Request;
use Udoktor\User;
use Udoktor\V_person;
use Auth;
use Udoktor\Http\Controllers\Controller;

/**
 * this class process login requests
 *
 * @package Udoktor\Http\Controllers
 * @category Controller
 * @author Gerardo Adrián Gómez Ruiz <gerardo.gomr@gmail.com>
 */
class LoginController extends Controller
{
    /**
     * shows login view
     *
     * @return Illuminate\Support\Facades\View
     */
    public function index(){
        return view('login.login');
    }

    /**
     * process login
     *
     * @param  Request $request
     * @return [type]
     */
    public function store(Request $request)
    {
        $usuario = $request->get('usuario');
        $pass    = $request->get('password');
        $origen  = $request->get('origen');

        if(Auth::attempt(['email'=>$usuario,'password'=>$pass,'confirmationtoken'=>'1'])){

            $activo=  User::where('email',$usuario)->where('active',true)->count();
            if($activo==0){
                return view('login.login')->with('cuentainactiva','si');
            }


            $idPerson = Auth::user()->personid;
            $dataPerson=V_person::find($idPerson);

            if(count($dataPerson)>0){
                if($origen==0){
                    if($dataPerson->isserviceprovider==true){
                        return redirect('prestadorServicios');
                    }else if($dataPerson->isclient==true){
                        return redirect('cliente');
                    }else if($dataPerson->isadmin==true){
                        return redirect('admin');
                    }else{
                        return redirect('cliente');
                    }
                }else{
                    return response()->json(['usuarioexiste' => true, 'id' => $idPerson, 'nombre'=>$dataPerson->firstname . " " . $dataPerson->lastname]);
                }
            }else{
                if($origen==0){
                    return view('login.login')->with('usuarioInvalido','si');
                }else{
                    return response()->json(['usuarioexiste' => false, 'id' => 0,'nombre'=>'']);
                }
            }
        }else{

            if($origen==0){
                return view('login.login')->with('usuarioInvalido','si');
            }else{
                return response()->json(['usuarioexiste' => false, 'id' => 0,'nombre'=>'']);
            }
        }
    }
}

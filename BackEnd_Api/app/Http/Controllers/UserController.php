<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Illuminate\Support\Facades\DB;
use App\User;
use App\Helpers\JwtAuth;

class UserController extends Controller
{
    public function register(Request $request)
    {
        //Se recogen las variables llegadas por POST
        $json = $request -> input('json', null);
        $params = json_decode($json);

        $Rol = '';
        $TipoDocumento = (!is_null($json) && isset($params->TipoDocumento)) ? $params->TipoDocumento : null;
        $NumeroDocumento = (!is_null($json) && isset($params->NumeroDocumento)) ? $params->NumeroDocumento : null;
        $Nombres = (!is_null($json) && isset($params->Nombres)) ? $params->Nombres : null;
        $Apellidos = (!is_null($json) && isset($params->Apellidos)) ? $params->Apellidos : null;
        $Genero = (!is_null($json) && isset($params->Genero)) ? $params->Genero : null;
        $Edad = (!is_null($json) && isset($params->Edad)) ? $params->Edad : null;
        $Contrasena = (!is_null($json) && isset($params->Contrasena)) ? $params->Contrasena : null;
        $Email = (!is_null($json) && isset($params->Email)) ? $params->Email : null;
        $Codigo = (!is_null($json) && isset($params->Codigo)) ? $params->Codigo : null;
        
        if(!is_null($TipoDocumento) && !is_null($NumeroDocumento) && !is_null($Nombres) && !is_null($Apellidos) 
        && !is_null($Genero) && !is_null($Edad) && !is_null($Contrasena) && !is_null($Email))
        {
            //Creacion de usuario
            $user = new User();
            $user->Rol = $Rol;
            $user->TipoDocumento = $TipoDocumento;
            $user->NumeroDocumento = $NumeroDocumento;
            $user->Nombres = $Nombres;
            $user->Apellidos = $Apellidos;
            $user->Genero = $Genero;
            $user->Edad = $Edad;
            $user->Email = $Email;
            $user->Codigo = $Codigo;
            //Encriptacion de password
            $pwd = hash('sha256', $Contrasena);
            $user->Contrasena = $pwd;

            //Comprobacion de duplicidad de usuarios
            $isset_user = User::where('NumeroDocumento','=', $NumeroDocumento)->first();

            if($isset_user == null)
            {
                //Guarda usuario registrado
                $user->save();

                $data =  array(
                    'status'=> 'success',
                    'code'=> 200,
                    'message' => 'Usuario registrado correctamente'
                );


            }else
            {
                //No guardar por que estaria duplicado
                $data =  array(
                    'status'=> 'error',
                    'code'=> 400,
                    'message' => 'El usuario ya esta registrado'
                );
            }

        }else
        {
            $data =  array(
                'status'=> 'error',
                'code'=> 400,
                'message' => 'Usuario no creado'
            );
        }
        return response()->json($data, 200);
        


        
    }
    public function login(Request $request)
    {
        $jwtAuth = new JwtAuth();

        //Recibir POST
        $json = $request->input('json', null);
        $params = json_decode($json);

        $NumeroDocumento = (!is_null($json) && isset($params->NumeroDocumento)) ? $params->NumeroDocumento : null;
        $Contrasena = (!is_null($json) && isset($params->Contrasena)) ? $params->Contrasena : null;
        $getToken = (!is_null($json) && isset($params->getToken)) ? $params->getToken : null;


        //Cifrar el password
        $pwd = hash('sha256', $Contrasena);

        if(!is_null($NumeroDocumento) && !is_null($Contrasena) && ($getToken == null || $getToken == 'false'))
        {
            $signup = $jwtAuth->signup($NumeroDocumento,$pwd);

        }elseif($getToken != null)
        {
            $signup = $jwtAuth->signup($NumeroDocumento,$pwd, $getToken);

        }else
        {
            $signup = array(
                'status' => 'error',
                'message' => 'Envia tus datos por POST'
            );
        }
        return response()->json($signup, 200);
    }

    public function update ($id, Request $request)
    {
        $hash = $request->header('Authorization', null);

        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);

        if($checkToken)
        {
            //Recoger parametros POST
            $json = $request->input('json', null);
            $params = json_decode($json);
            $parmas_array = json_decode($json, true);
            $pwd = hash('sha256', $parmas_array['Contrasena']);
            $parmas_array['Contrasena'] = $pwd;

            //Validar datos

            $validate = \Validator::make($parmas_array, [
                'TipoDocumento' => 'required',
                'NumeroDocumento' => 'required',
                'Edad' => 'required',
                'Contrasena' => 'required',
                'Email' => 'required',
                'Codigo' => 'required',
                
            ]);
        if($validate->fails())
        {
            return response()->json($validate->errors(),400);
        }
        unset($parmas_array['IdUsuario']);
        unset($parmas_array['Rol']);
        unset($parmas_array['Nombres']);
        unset($parmas_array['Apellidos']);
        unset($parmas_array['Genero']);
        unset($parmas_array['iat']);
        unset($parmas_array['exp']);
        unset($parmas_array['created_at']);
        unset($parmas_array['updated_at']);


           //Actualizar registro
        
            $user = User::where('IdUsuario', $id)->update($parmas_array);

            $data= array(
                'user' => $params,
                'status' => 'success',
                'code' => 200,
            );

        }else
        {
           //Devolver error
           $data= array(
            'message' => 'Login incorrecto',
            'status' => 'error',
            'code' => 400,
        );
        }

        return response()->json($data, 200);
    }

    public function forgetpass(Request $request)
    {
        //Recoger parametros POST
        $json = $request->input('json', null);
        $params = json_decode($json);
        $parmas_array = json_decode($json, true);
        $pwd = hash('sha256', $parmas_array['Contrasena']);
        $parmas_array['Contrasena'] = $pwd;    

        if( isset($parmas_array['NumeroDocumento']) &&  isset($parmas_array['Codigo']) &&  isset($parmas_array['Contrasena']))
        {
            //Validar datos

            $validate = \Validator::make($parmas_array, [
                'NumeroDocumento' => 'required',
                'Contrasena' => 'required',
                'Codigo' => 'required',
                
            ]);
            if($validate->fails())
            {
                return response()->json($validate->errors(),400);
            }
            unset($parmas_array['IdUsuario']);
            unset($parmas_array['Rol']);
            unset($parmas_array['Nombres']);
            unset($parmas_array['Apellidos']);
            unset($parmas_array['Genero']);
            unset($parmas_array['TipoDocumento']);
            unset($parmas_array['Edad']);
            unset($parmas_array['Email']);
            unset($parmas_array['created_at']);
            unset($parmas_array['updated_at']);


           //Actualizar registro
        
            $user = User::where('NumeroDocumento', '=', $parmas_array['NumeroDocumento'])
                                ->where('Codigo', '=', $parmas_array['Codigo'])
                                ->update($parmas_array);

            $data= array(
                'user' => $params,
                'status' => 'success',
                'code' => 200,
            );

        }else{
           //Devolver error
           $data= array(
            'message' => 'Faltan datos',
            'status' => 'error',
            'code' => 400,
        );
        }
        return response()->json($data, 200);
    }
    
    
}

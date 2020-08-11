<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\JwtAuth;
use App\ubication;

class UbicationController extends Controller
{
    public function indexFromId($id ,Request $request)
    {
        $ubication = DB::table('ubication')->Where('IdUsuario','=',$id)->get();
        return response()->json(array(
            'ubication'=>$ubication,
            'status' => 'success'
        ), 200);
        
    }
    public function store( Request $request)
    {
        $hash = $request->header('Authorization', null);

        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);

        if($checkToken)
        {
            //Recoger datos por POST
            $json = $request->input('json', null);
            $params = json_decode($json);
            $parmas_array = json_decode($json, true);
            
            //Conseguir el usuario identificado
            $user = $jwtAuth->checkToken($hash, true);

            
            //Validacion
            
                $validate = \Validator::make($parmas_array, [
                    'NombreUbicacion' => 'required',
                    'Latitud' => 'required',
                    'Longitud' => 'required',
                ]);
            if($validate->fails())
            {
                return response()->json($validate->errors(),400);
            }
            //Guardar Ubicacion
            $ubication = new ubication();
            $ubication->IdUsuario = $user->IdUsuario;
            $ubication->NombreUbicacion = $params->NombreUbicacion;
            $ubication->Latitud = $params->Latitud;
            $ubication->Longitud = $params->Longitud;

            $ubication->save();

            $data= array(
                'ubication' => $ubication,
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

    public function destroy ($Id, Request $request)
    {
        $hash = $request->header('Authorization', null);

        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);

        if($checkToken)
        {
            //Comprobar si existe el registro
            $ubication = ubication::find($Id);

            //Borrarlo
            $ubication->delete();

            //Devolverlo
            $data = array(
                'ubication'=> $ubication,
                'status' => 'success',
                'code' => 200
            );
        }else
        {
            $data = array(
                'status' => 'error',
                'code' => 400,
                'message' => 'Login incorrecto!!'
            );
        }

        return response()->json($data, 200);
    }
}
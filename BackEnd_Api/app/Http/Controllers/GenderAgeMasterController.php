<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\JwtAuth;
use App\GenderAgeMaster;

class GenderAgeMasterController extends Controller
{
    public function index(Request $request)
    {
        $genderagemaster = GenderAgeMaster::all();
        return response()->json(array(
            'genderagemaster'=>$genderagemaster,
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
                    'Genero' => 'required',
                    'EdadInicial' => 'required',
                    'EdadFinal' => 'required',
                    'Cifra' => 'required',
                    
                ]);
            if($validate->fails())
            {
                return response()->json($validate->errors(),400);
            }
            //Guardar 
            $genderagemaster = new GenderAgeMaster();
            $genderagemaster->Genero = $params->Genero;
            $genderagemaster->EdadInicial = $params->EdadInicial;
            $genderagemaster->EdadFinal = $params->EdadFinal;
            $genderagemaster->Cifra = $params->Cifra;

            $genderagemaster->save();

            $data= array(
                'genderagemaster' => $genderagemaster,
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

    public function update($id, Request $request)
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

            //Validar datos

            $validate = \Validator::make($parmas_array, [
                'Genero' => 'required',
                'EdadInicial' => 'required',
                'EdadFinal' => 'required',
                'Cifra' => 'required',
                
            ]);
        if($validate->fails())
        {
            return response()->json($validate->errors(),400);
        }
        unset($parmas_array['IdGeneroEdad']);
        unset($parmas_array['created_at']);
        unset($parmas_array['updated_at']);

           //Actualizar registro
        
            $genderagemaster = GenderAgeMaster::where('IdGeneroEdad', $id)->update($parmas_array);

            $data= array(
                'genderagemaster' => $params,
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
}

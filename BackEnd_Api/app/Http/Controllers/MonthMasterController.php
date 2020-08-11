<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\JwtAuth;
use App\MonthMaster;

class MonthMasterController extends Controller
{
     public function index(Request $request)
    {
        $monthmaster = MonthMaster::all();
        return response()->json(array(
            'monthmaster'=>$monthmaster,
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
                    'NombreMes' => 'required',
                    'Cifra' => 'required',
                    
                ]);
            if($validate->fails())
            {
                return response()->json($validate->errors(),400);
            }
            //Guardar Ubicacion
            $monthmaster = new MonthMaster();
            $monthmaster->NombreMes = $params->NombreMes;
            $monthmaster->Cifra = $params->Cifra;

            $monthmaster->save();

            $data= array(
                'monthmaster' => $monthmaster,
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
                'NombreMes' => 'required',
                'Cifra' => 'required',
                
            ]);
        if($validate->fails())
        {
            return response()->json($validate->errors(),400);
        }

           //Actualizar registro
            unset($parmas_array['IdMes']);
            unset($parmas_array['created_at']);
            unset($parmas_array['updated_at']);

            $monthmaster = MonthMaster::where('IdMes', $id)->update($parmas_array);
      

            $data= array(
                'monthmaster' => $params,
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

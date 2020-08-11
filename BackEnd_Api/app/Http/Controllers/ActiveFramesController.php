<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ActiveFrames;
use App\Helpers\JwtAuth;

class ActiveFramesController extends Controller
{
    public function index(Request $request)
    {
        $activeframes = ActiveFrames::all();
        return response()->json(array(
            'activeframes'=>$activeframes,
            'status' => 'success'
        ), 200);
        
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
                'FrameMapa' => 'required',
                'FrameIndicaciones' => 'required',
                'FrameIBOCA' => 'required',
                'FrameCovid' => 'required',
                
            ]);
        if($validate->fails())
        {
            return response()->json($validate->errors(),400);
        }
        unset($parmas_array['created_at']);
        unset($parmas_array['updated_at']);
           //Actualizar registro
        
            $activeframes = ActiveFrames::where('IdFramesActivos', $id)->update($parmas_array);

            $data= array(
                'activeframes' => $params,
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\JwtAuth;
use App\WeekHourMaster;

class WeekHourMasterController extends Controller
{
    public function index(Request $request)
    {
        $weekhourmaster = WeekHourMaster::all();
        return response()->json(array(
            'weekhourmaster'=>$weekhourmaster,
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
                    'HoraInicial' => 'required',
                    'HoraFinal' => 'required',
                    'DiaSemana' => 'required',
                    'Cifra' => 'required',
                    
                ]);
            if($validate->fails())
            {
                return response()->json($validate->errors(),400);
            }

            //Guardar 
            $weekhourmaster = new WeekHourMaster();
            $weekhourmaster->HoraInicial = $params->HoraInicial;
            $weekhourmaster->HoraFinal = $params->HoraFinal;
            $weekhourmaster->DiaSemana = $params->DiaSemana;
            $weekhourmaster->Cifra = $params->Cifra;

            $weekhourmaster->save();

            $data= array(
                'weekhourmaster' => $weekhourmaster,
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
                'HoraInicial' => 'required',
                'HoraFinal' => 'required',
                'DiaSemana' => 'required',
                'Cifra' => 'required',
                
            ]);
        if($validate->fails())
        {
            return response()->json($validate->errors(),400);
        }
        unset($parmas_array['IdIdHoraSemana']);
        unset($parmas_array['created_at']);
        unset($parmas_array['updated_at']);
           //Actualizar registro
        
            $weekhourmaster = WeekHourMaster::where('IdHoraSemana', $id)->update($parmas_array);

            $data= array(
                'weekhourmaster' => $params,
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

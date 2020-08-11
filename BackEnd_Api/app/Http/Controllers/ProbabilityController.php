<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\JwtAuth;
use App\MonthMaster;
use App\LocationMaster;
use App\GenderAgeMaster;
use App\WeekHourMaster;
use App\DataMaster;

class ProbabilityController extends Controller
{
    public function calculateProbability(Request $request)
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
                    'Mes' => 'required',
                    'Localidad' => 'required',
                    'Edad' => 'required',
                    'Genero' => 'required',
                    'Hora' => 'required',
                    'DiaSemana' => 'required',

                ]);
            if($validate->fails())
            {
                return response()->json($validate->errors(),400);
            }

            //Traer datos para el calculo
            $MonthNumber = DB::table('monthmaster')->Where('NombreMes',$parmas_array['Mes'])->get(['Cifra']);
            foreach($MonthNumber as $monthNumber)
            {
                $valorMonth = $monthNumber->Cifra;
            }
            $LocationNumber = DB::table('locationmaster')->Where( 'NombreLocalidad',$parmas_array['Localidad'])->get(['Cifra']);
            foreach($LocationNumber as $locationNumber)
            {
                $valorLocation = $locationNumber->Cifra;
            }
            $AgeGenreNumber = DB::table('genderagemaster')->Where( 'Genero',$parmas_array['Genero'])
                                                ->Where('EdadInicial','<=',$parmas_array['Edad'] )
                                                ->Where( 'EdadFinal','>=',$parmas_array['Edad'])
                                                ->get(['Cifra']);
             foreach($AgeGenreNumber as $ageGenreNumber)
            {
                $valorAgeGenre = $ageGenreNumber->Cifra;
            }
            $WeekHourNumber = DB::table('weekhourmaster')->Where('HoraInicial',$parmas_array['Hora'])
                                            ->Where('DiaSemana',$parmas_array['DiaSemana'])
                                            ->get(['Cifra']);
            foreach($WeekHourNumber as $weekHourNumber)
            {
                $valorWeekHour = $weekHourNumber->Cifra;
            }
            
            $DataMasterNumber = DB::table('datamaster')->first();

            //Calculo Individual
            $ProbabilityMonth = ($valorMonth/$DataMasterNumber->Cifra)*100;
            $ProbabilityLocation = ($valorLocation/$DataMasterNumber->Cifra)*100;
            $ProbabilityAgeGenre = ($valorAgeGenre/$DataMasterNumber->Cifra*100);
            $ProbabilityWeekHour = ($valorWeekHour/$DataMasterNumber->Cifra)*100;

            //Calculo total
            $ProbabilityDanger = ($ProbabilityMonth * $ProbabilityLocation * $ProbabilityAgeGenre * $ProbabilityWeekHour) ;

            $data= array(
                'ProbabilityDanger' => $ProbabilityDanger,
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

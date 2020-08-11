<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\JwtAuth;
use App\Questions;
use App\HeadInformationQuestions;
use App\DetailInformationQuestions;
use App\IBOCA;
use Illuminate\Support\Facades\DB;

class InformationQuestionController extends Controller
{
    public function LoadQuestions(Request $request)
    {
        $questions = Questions::all();
        return response()->json(array(
            'questions'=>$questions,
            'status'=>'success'

        ), 200);
    }

    public function SaveQuestions(Request $request)
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
                    'IdUsuario' => 'required',
                    'IdHeadInformationDetail' => 'required',
                    'Contestado' => 'required',
                    'Detail' => 'required',

                    
                    
                ]);
            if($validate->fails())
            {
                return response()->json($validate->errors(),400);
            }
            $findRegister = HeadInformationQuestions::where('IdUsuario',$params->IdUsuario)->first();
            if(is_null($findRegister))
            {
                //Guardar datos Cabecera
                $informationquestions = new HeadInformationQuestions();
                $informationquestions->IdUsuario = $params->IdUsuario;
                $informationquestions->IdHeadInformationDetail = "1-".$params->IdHeadInformationDetail;
                $informationquestions->Contestado = $params->Contestado;

                $informationquestions->save();
            }    
            //Guardar datos detalle
            foreach($params->Detail as $paramsDetail)
            {
            $informationdetail = new DetailInformationQuestions();
            $informationdetail->IdQuestions = $paramsDetail->IdQuestions;
            $informationdetail->IdHeadInformationDetail = "1-".$params->IdHeadInformationDetail;
            $informationdetail->Valor = $paramsDetail->Valor;           
            $informationdetail->save();
            }

            
            
            $data= array(
                'questions' => "1-".$params->IdHeadInformationDetail,
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

    public function updateQuestions($id, Request $request)
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
                'Detail' => 'required',
                
            ]);
        if($validate->fails())
        {
            return response()->json($validate->errors(),400);
        }

           //Actualizar registro
            unset($parmas_array['IdDetailInformatioQuestions']);
            unset($parmas_array['IdQuestions']);
            unset($parmas_array['IdHeadInformationDetail']);
            unset($parmas_array['created_at']);
            unset($parmas_array['updated_at']);
            $findHead = HeadInformationQuestions::Select('IdHeadInformationDetail')->where('IdUsuario',$id)->get()->toArray();
            $findDetail = DetailInformationQuestions::where('IdHeadInformationDetail',$findHead)->get();
        
            foreach($findDetail as $fiDet)
            {
                $numberQuest = $fiDet->IdQuestions;
                foreach($params as $inputDet)
                {
                    foreach($inputDet as $prub)
                    {
                       if($numberQuest = $prub->IdQuestions)
                       {
                           $fiDet->Valor = $prub->Valor;
                           $fiDet->save();
                       }
                    }  
                }
            }
                    
            $data= array(
                'updateDetail' => $fiDet->IdHeadInformationDetail,
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

    public function searchAnswersHead($id)
    {
        $searchAnswers = HeadInformationQuestions::where('IdUsuario',$id)->get();
        if(count($searchAnswers) == 0)
        {
            $bool = 'false';
        }else
        {
            $bool = 'true';
        }
        return response()->json(array(
            'searchAnswers'=>$searchAnswers,
            'status'=>'success',
            'noEmpty'=>$bool

        ), 200);
    }

    public function findAnswersDetail($id)
    {
        $searchhead = HeadInformationQuestions::Select('IdHeadInformationDetail')->where('IdUsuario',$id)->get()->toArray();
        $searchDetail = DetailInformationQuestions::where('IdHeadInformationDetail',$searchhead)
                                                    ->where('Valor','SI')->get();
        
        return response()->json(array(
            'searchDetail'=>$searchDetail,
            'status'=>'success'
        ),200);
    }

    public function findLocationIboca($localidad)
    {
        $findIBOCA = IBOCA::Select('ValorMedidor')->where('Localidad',$localidad)->get();

        return response()->json(array(
            'findIBOCA'=>$findIBOCA,
            'status'=>'success'
        ),200);
    }

}

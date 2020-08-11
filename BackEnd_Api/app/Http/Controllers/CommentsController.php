<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\JwtAuth;
use App\Comments;

class CommentsController extends Controller
{
    public function getComments(Request $request)
    {
        $comments = Comments::All();
        return response()->json(array(
            'comments'=>$comments,
            'status' => 'success'
        ), 200);
        
    }

    public function insert( Request $request)
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
                    'RouteComment' => 'required',
                    
                ]);
            if($validate->fails())
            {
                return response()->json($validate->errors(),400);
            }
            //Guardar 
            $comment = new Comments();
            $comment->RouteComment = $params->RouteComment;
            $comment->ReadFlag = 'NO';

            $comment->save();

            $data= array(
                'comment' => $comment,
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

    public function modify($id, Request $request)
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
                'ReadFlag' => 'required',
                
            ]);
        if($validate->fails())
        {
            return response()->json($validate->errors(),400);
        }
        unset($parmas_array['created_at']);
        unset($parmas_array['updated_at']);
           //Actualizar registro
        
            $comment = Comments::where('CommentsId', $id)->update($parmas_array);

            $data= array(
                'comment' => $params,
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

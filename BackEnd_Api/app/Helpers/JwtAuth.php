<?php
namespace App\Helpers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\User;

class JwtAuth
{
    public $key;

    public function __construct()
    {
        $this->key = 'usuario-unilibre-bicis*7819971539532871';
    }

    public function signup($NumeroDocumento, $Contrasena, $getToken = null)
    {
        $user = User::where(
            array(
                'NumeroDocumento' => $NumeroDocumento,
                'Contrasena' => $Contrasena 

        ))->first();
        $signup = false;
        if(is_object($user))
        {
            $signup = true;
        }

        if($signup)
        {
            //Generar token y devolverlo
            $token = array(
                'IdUsuario' => $user->IdUsuario,
                'Nombres' => $user->Nombres,
                'Apellidos' => $user->Apellidos,
                'TipoDocumento' => $user->TipoDocumento,
                'NumeroDocumento' => $user->NumeroDocumento,
                'Codigo' => $user->Codigo,
                'Email' => $user->Email,
                'Genero' => $user->Genero,
                'Edad'=> $user->Edad,
                'Rol' => $user->Rol,
                'iat' => time(),
                'exp' => time() + (7 * 24 * 60 * 60)
            );
            $jwt = JWT::encode($token, $this->key, 'HS256');
            $decode = JWT::decode($jwt, $this->key, array('HS256'));

            if(is_null($getToken))
            {
                return $jwt;
            }else
            {
                return $decode;
            }

        }else
        {
            //Devolver error
            return array('status' => 'error', 'message' => 'Login ha fallado !!');
        }

    }

    public function checkToken($jwt, $getIdentity = false)
    {
        $auth = false;
        try
        {
            $decode = JWT::decode($jwt, $this->key, array('HS256'));
 
        }
        catch(\UnexpectedValueException $e)
        {
            $auth = false;
        }
        catch(\DomainException $e)
        {
            $auth = false;
        }

        if(isset($decode) && isset($decode->IdUsuario) && is_object($decode))
        {
            $auth = true;
        }else
        {
            $auth = false;
        }
        if($getIdentity)
        {
            return $decode;
        }
        return $auth;
    }
}
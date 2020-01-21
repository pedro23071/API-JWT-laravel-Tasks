<?php
namespace App\Helpers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\User;

class JwtAuth{

    public $key = 'panc23071@';

    public function signUp($email, $password, $getToken = false){
            $user = User::where([
                'email' => $email,
                'password' => $password
            ])->first();

            if (is_object($user)){
                //un array con los datos del usuario, lo convertimos en token y lo devolvemos
                $token = [
                    'sub' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'iat' => time(),
                    'exp' => time() + (7 * 24 * 60 * 60)
                ];

                $jwt = JWT::encode($token, $this->key);
                $decoded = JWT::decode($jwt, $this->key, array('HS256'));

                if($getToken){
                    return $decoded;
                }else{
                    return $jwt;
                }

            }else{
                //devolver un error
                return [
                    'status' => 'error',
                    'mesage' => 'el login a fallado'
                ];
            }
    }

    public function checkToken($jwt, $getIdentity = false){
        $auth = false;

        try {
            $decoded = JWT::decode($jwt, $this->key, array('HS256'));
        }catch (\UnexpectedValueException $e){
            $auth = false;
        }catch (\DomainException $e){
            $auth = false;
        }
        //var_dump($decoded); die();
        if (isset($decoded) && is_object($decoded) && isset($decoded->sub)){
            $auth = true;
        }else{
            $auth = false;
        }

        if($getIdentity){
            return $decoded;
        }

        return $auth;
    }
}

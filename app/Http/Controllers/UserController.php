<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\User;
use App\Helpers\JwtAuth;

class UserController extends Controller
{
    public function createUser(Request $request){


        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
        }

        $input = $request->all();
        $input['password'] = hash('sha256', $input['password']);
        $user = User::create($input);

        return $response = [
            'success' => true,
            'message' => 'Usuario registrado exitozamente',
            'user' => $user
        ];
    }

    public function logIn(Request $request){
        $jwtauth = new JwtAuth;

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
        }


        $email = $request->email;
        $password = hash('sha256', $request->password);
        $getToken = $request->getToken;
        $getToken = ($request->getToken == 'true') ? true : false;

        //var_dump($password); die();

        return response()->json($jwtauth->signUp($email, $password, $getToken), 200);
    }


}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function register(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'name' => 'required|max:20|min:2',
            'email' => 'required|unique:users|email:rfc,dns',
            'password' => 'required|min:6',
        ]);
        if ($validator->fails()) {
            $output['success'] = false;
            $output['data'] = $validator->errors()->first();
            return response()->json($output);
        } else {
            $output['success'] = true;
            $user = User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => bcrypt($request['password'])
            ]);
            $token = $user->createToken('myapptoken')->plainTextToken;
            $user['token'] = $token;
            $output['data']= $user;
            return response($output, 201);
        }


    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            $output['success'] = false;
            $output['data'] = $validator->errors()->first();
            return response()->json($output);
        }
        else{
            // Check email
            $user = User::where('email', $request['email'])->first();

            // Check password
            if (!$user || !Hash::check($request['password'], $user->password)) {
                return response([
                    'message' => 'Bad creds'
                ], 401);
            }

            $token = $user->createToken('myapptoken')->plainTextToken;
            $output['success'] = true;
            $user['token'] = $token;
            $output['data'] = $user;



            return response($output, 200);
        }


    }
}

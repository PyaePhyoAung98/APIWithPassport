<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function register(Request $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        $token = $user->createToken('Token Api')->accessToken;

        return response()->json([
            'result' => 1,
            'message' => 'success',
            'token' => $token
        ]);
    }
    public function login(Request $request)
    {
        $data = $request->only('email', 'password');
        if (Auth::attempt($data)) {

            $user = Auth::user();

            $token = $user->createToken('Token Name')->accessToken;

            return response()->json([
                'result' => 1,
                'message' => 'success',
                'token' => $token
            ]);
        } else {
            return response()->json([
                'result' => 0 ,
                'message' => 'fail',

            ]);
        }
    }
    public function profile()
    {
        $user = Auth::user();
        $data=new UserResource($user);
       // UserResource::collection($user);//multi
        return response()->json([
            'result'=>1,
            'message'=>'sucess',
            'user'=>$data
        ]);
    }
    public function logout()
    {
        Auth::user()->token()->revoke();
        return response()->json([
            'result'=>1,
            'message'=>'sucess',
            
        ]);
    }
}

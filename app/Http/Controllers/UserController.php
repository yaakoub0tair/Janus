<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //
    public function create(Request $request)
    {
        $valide = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed'
        ]);

        $user = User::create($valide);

        return response()->json($user);
    }

    public function login(Request $request)
    {
        $valid =$request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        if(Auth::attempt($valid)){
            $user=User::where('email',$request->input('email'))->first();
            $token=$user->createToken('api_login');
            return response()->json(["token"=>$token]);
            
        }
        return response()->json(["messages"=>"login ou ot d pass incorect"])->status(403);

    }
    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return response()->json(["success"=> true, "message"=>"logout"]);

    }
}

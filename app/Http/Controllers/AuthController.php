<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginEscola(Request $request){
        if(Auth::attempt($request->only('username', 'password'))){
            return "Authorized";
        }else{
            return "foi nao";
        }
    }
    public function logoutEscola(){}
}

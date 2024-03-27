<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    function resposta($codigo, $ok, $msg)
    {
        http_response_code($codigo);
        echo (json_encode([
            'ok' => $ok,
            'msg' => $msg
        ]));
    }
    public function loginEscola(Request $request)
    {
        if (Auth::attempt($request->only('username', 'password'))) {
            return $this->resposta(200, true, [
                'token' => $request->user()->createToken('invoice')->plainTextToken
            ]);
        } else {
            return "foi nao";
        }
    }
    public function logoutEscola()
    {
    }
}

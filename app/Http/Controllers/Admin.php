<?php

namespace App\Http\Controllers;

use App\Models\Escola;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Admin extends Controller
{
    public function login(Request $request) {
        if ($request['username'] == "normane136ec5") {
            if (Auth::attempt($request->only('username', 'password'))) {
                return response()->json([
                    "token" => $request->user()->createToken('loginAdmin')->plainTextToken
                ], 200);
            }
        }
        return response()->json([
            "msg" => "Você não tem autorização para isso",
        ], 401);
    }
    public function getEscolas () {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->username == "normane136ec5") {
                return Escola::all();
            }
        }
        return response()->json([
            "msg" => "Você não tem autorização para isso",
        ], 401);
    }
}

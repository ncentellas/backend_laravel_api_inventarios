<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        //validar
        $credenciales = $request->validate([
            "email" => "required|email",
            "password" => ["required", Password::min(6)             //Minimo 8 caracteres
                //->mixedCase()        //Al menos una letra mayuscula y una minuscula
                //->letters()          //Al menos una letra
                //->numbers()          //Al menos un numero
                //->symbols()        //Al menos un simbolo
            ]
        ]);
        //autenticar
        if (!Auth::attempt($credenciales)) {
            return response()->json([
                "mensaje" => "Credenciales incorrectos"
            ], 401);
        }
        //generar token
        $token = $request->user()->createToken("TokenAuth")->plainTextToken;
        //responder el usuario + token
        return response()->json([
            "access_token" => $token,
            "usuario" => $request->user()
        ]);
    }
    public function register(Request $request)
    {
        //validacion
        $request->validate([
            "name"=> "required",
            "email"=> "required|email|unique:users",
            "password"=> "required"
        ]);
        //registro
        $usuario = new User();
        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->password = Hash::make($request->password);
        $usuario->save();
        //respuesta
        return response()->json(["mensaje"=>"Usuario registrado correctamente"], 201);
    }
    public function profile(Request $request)
    {
        return response()->json($request->user(), 200);
    }
    public function logout(Request $request) {
        $request->user()->tokens()->delete();
        return response()->json(["mensaje"=>"Logout"]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ApiTokenController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return Response
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $token = env('SERVIDOR_ID').".".Str::random(75);

            $request->user()->forceFill([
                'api_token' => hash('sha256', $token),
            ])->save();
    
            return ['token' => $token];
        } else {
            return  response()->json("Credenciales inválidas", 401);
        }
    }
}

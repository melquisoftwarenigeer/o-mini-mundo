<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;

class VerifyJwtTokenWeb
{
    public function handle(Request $request, Closure $next)
    {
        try {
            $token = $request->bearerToken() ?: session('token');

            if (!$token) {
                // Defina uma mensagem de erro na sessão antes de redirecionar
                session()->flash('error', 'Acesso não autorizado. Você precisa estar logado para acessar essa página.');
                return redirect()->route('login');
            }

            JWTAuth::setToken($token)->authenticate();
        } catch (Exception $e) {
            // Se ocorrer algum erro, defina uma mensagem de erro na sessão
            session()->flash('error', 'Acesso não autorizado. Token inválido ou expirado.');
            return redirect()->route('login');
        }

        return $next($request);
    }
}

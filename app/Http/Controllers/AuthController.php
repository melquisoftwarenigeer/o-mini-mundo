<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Exception;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        try {

            $credentials = $request->only(['email', 'password']);

            // Tentativa de autenticação com as credenciais fornecidas
            $token = auth('web')->attempt($credentials);

            if (!$token) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Credenciais inválidas'
                ], 401);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Login realizado com sucesso',
                'token' => $token
            ], 200);
        } catch (ValidationException $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Erro de validação',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {

            Log::error('Erro no login: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao realizar login',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            //Tentando pegar o token do cabeçalho Authorization
            $token = $request->bearerToken() ?: session('token');

            // Remove o token da sessão
            Session::forget('token');

            // Limpa o cookie de token
            Cookie::queue(Cookie::forget('token'));

            // Retorna uma resposta de sucesso
            return response()->json([
                'status' => 'success',
                'message' => 'Logout realizado com sucesso'
            ], 200);
        } catch (Exception $e) {
            Log::error('Erro ao realizar logout: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao realizar logout',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
            ]);


            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            $token = auth('web')->login($user);

            return response()->json([
                'status' => 'success',
                'message' => 'Usuário registrado com sucesso',
                'user' => $user,
                'token' => $token
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro de validação',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            Log::error('Erro ao registrar usuário: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao registrar usuário',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

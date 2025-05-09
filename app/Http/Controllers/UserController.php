<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
   
    public function index()
    {
        try {
            $users = User::all();

            return response()->json([
                'status' => 'success',
                'message' => 'Lista de usuários',
                'data' => $users
            ]);
        } catch (Exception $e) {
            Log::error('Erro ao listar usuários: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao recuperar os dados',
                'error' => $e->getMessage()
            ], 500);
        }
    }

   
    public function store(Request $request)
    {
      
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6|confirmed',
            ]);

            $validated['password'] = Hash::make($validated['password']);

            $user = User::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Usuário criado com sucesso',
                'data' => $user
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro de validação',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            Log::error('Erro ao criar usuário: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao criar usuário',
                'error' => $e->getMessage()
            ], 500);
        }
    }

 
    public function show(User $user)
    {
        try {
            return response()->json([
                'status' => 'success',
                'message' => 'Detalhes do usuário',
                'data' => $user
            ]);
        } catch (Exception $e) {
            Log::error('Erro ao mostrar usuário: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao recuperar o usuário',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function update(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:6|confirmed',
            ]);

            if (isset($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            }

            $user->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Usuário atualizado com sucesso',
                'data' => $user
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro de validação',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            Log::error('Erro ao atualizar usuário: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao atualizar o usuário',
                'error' => $e->getMessage()
            ], 500);
        }
    }

  
    public function destroy(User $user)
    {
        try {
            $user->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Usuário excluído com sucesso'
            ], 204);
        } catch (Exception $e) {
            Log::error('Erro ao excluir usuário: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao excluir o usuário',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

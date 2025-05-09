<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $projects = Project::all();

            return response()->json([
                'status' => 'success',
                'message' => 'Lista de projetos',
                'data' => $projects
            ]);
        } catch (Exception $e) {
            Log::error('Erro ao listar projetos: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao recuperar os dados',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        try {

            $validated = $request->validate([
                'name' => 'required|unique:projects,name',
                'description' => 'nullable|string',
                'status' => 'in:Ativo,Inativo',
                'budget' => 'nullable|numeric|min:0',
            ]);

            $validated['user_id'] = $user->id;

            $project = Project::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Inserido com sucesso',
                'data' => $project
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro de validação',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Erro ao cadastrar projeto: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Erro genérico',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {

        try {
            return response()->json([
                'status' => 'success',
                'message' => 'Detalhes do projeto',
                'data' => $project
            ]);
        } catch (Exception $e) {
            Log::error('Erro ao mostrar projeto: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao recuperar o projeto',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        $user = Auth::user();
        try {

            $validated = $request->validate([
                'name' => [
                    'required',
                    Rule::unique('projects')->ignore($project->id),
                ],
                'description' => 'nullable|string',
                'status' => 'in:Ativo,Inativo',
                'budget' => 'nullable|numeric|min:0',
            ]);


            $validated['user_id'] = $user->id;

            $project->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Atualizado com sucesso',
                'data' => $project
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro de validação',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            Log::error('Erro ao atualizar projeto: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Erro genérico',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        try {
            if ($project->tasks()->count() > 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Não é possível excluir projeto com tarefas associadas.'
                ], 400);
            }

            $project->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Projeto excluído com sucesso'
            ]); // <-- removido status 204 e mantido JSON
        } catch (Exception $e) {
            Log::error('Erro ao excluir projeto: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao excluir o projeto',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

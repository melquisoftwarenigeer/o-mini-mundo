<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $projectId = $request->input('project_id');
       
        if (!$projectId) {          
            try {
                $tasks = Task::with('project', 'predecessor')->orderBy('id','asc')->get();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Lista de tarefas',
                    'data' => $tasks
                ]);
            } catch (Exception $e) {
                Log::error('Erro ao listar tarefas: ' . $e->getMessage());
                return response()->json([
                    'status' => 'error',
                    'message' => 'Erro ao recuperar as tarefas',
                    'error' => $e->getMessage()
                ], 500);
            }
        }

        try {
            // Verifica se o project_id foi enviado
            if (!$projectId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Parâmetro project_id é obrigatório.'
                ], 400);
            }

            // Busca tarefas apenas do projeto especificado
            $tasks = Task::with('project', 'predecessor')
                ->where('project_id', $projectId)
                ->orderBy('id','asc')
                ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Lista de tarefas do projeto',
                'data' => $tasks
            ]);
        } catch (Exception $e) {
            Log::error('Erro ao listar tarefas: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao recuperar as tarefas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function indexAll()
    {
        $user = Auth::user();

        // Busca todas tarefas dos projetos do usuário
        $tasks = \App\Models\Task::whereHas('project', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->with('project')
            ->get();

        return view('tasks.index', compact('tasks'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($projectId)
    {
        // dd($projectId);
        $project = Project::findOrFail($projectId);

        // Tarefas do mesmo projeto que podem ser predecessoras
        $tasks = Task::where('project_id', $projectId)->get();

        return view('tasks.create', compact('project', 'tasks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'description' => 'required|string',
                'project_id' => 'required|exists:projects,id',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'predecessor_id' => 'nullable|exists:tasks,id',
                'status' => 'in:Pendente,Concluida,Em Andamento'
            ]);

            $task = Task::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Tarefa criada com sucesso',
                'data' => $task
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro de validação',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            Log::error('Erro ao cadastrar tarefa: ' . $e->getMessage());
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
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        try {
            $task->load('project', 'predecessor');

            return response()->json([
                'status' => 'success',
                'message' => 'Detalhes da tarefa',
                'data' => $task
            ]);
        } catch (Exception $e) {
            Log::error('Erro ao mostrar tarefa: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao recuperar a tarefa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit($projectId, $taskId)
    {
        $project = Project::findOrFail($projectId);
        $task = Task::where('project_id', $projectId)->findOrFail($taskId);

        return view('tasks.edit', compact('project', 'task'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {

        try {
            $validated = $request->validate([
                'description' => 'required|string',
                'project_id' => 'required|exists:projects,id',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'predecessor_id' => [
                    'nullable',
                    'exists:tasks,id',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->id && $value == $request->id) {
                            $fail('A tarefa não pode ser predecessora dela mesma.');
                        }
                    }
                ],
                'status' => 'in:Pendente,Concluida,Em Andamento'
            ]);

            $task->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Tarefa atualizada com sucesso',
                'data' => $task
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro de validação',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            Log::error('Erro ao atualizar tarefa: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Erro genérico',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, Task $task)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:Pendente,Concluida,Em Andamento'
            ]);

            $newStatus = $validated['status'];
            $currentStatus = $task->status;

            // Validação ao concluir tarefa
            if ($newStatus === 'Concluida') {
                if ($task->predecessor && $task->predecessor->status !== 'Concluida') {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Não é possível concluir esta tarefa pois a predecessora ainda não está concluída.'
                    ], 400);
                }

                foreach ($task->dependents as $dependent) {
                    if ($dependent->status === 'Concluida') {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Não é possível concluir esta tarefa pois há dependentes já concluídos.'
                        ], 400);
                    }
                }
            }

            // Validação ao reabrir tarefa (de Concluida para outro status)
            if ($currentStatus === 'Concluida' && $newStatus !== 'Concluida') {
                foreach ($task->dependents as $dependent) {
                    if ($dependent->status === 'Concluida') {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Não é possível reabrir esta tarefa pois há dependentes já concluídos.'
                        ], 400);
                    }
                }
            }

            $task->status = $newStatus;
            $task->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Status da tarefa atualizado com sucesso.',
                'data' => $task
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro de validação.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar status da tarefa: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao atualizar status.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        try {
            if ($task->dependents()->count() > 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Não é possível excluir a tarefa pois ela é predecessora de outra.'
                ], 400);
            }

            $task->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Tarefa excluída com sucesso'
            ]); // <-- status 200 e JSON OK
        } catch (Exception $e) {
            Log::error('Erro ao excluir tarefa: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao excluir a tarefa',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function projectTasks($projectId)
    {
        $tasks = Task::where('project_id', $projectId)->get();
        return response()->json($tasks);
    }
}

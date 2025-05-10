<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;
// use Illuminate\Foundation\Testing\WithFaker;

class TaskApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    protected $headers;

    protected function setUp(): void
    {
        parent::setUp();
        // Cria um usuário autenticado e gera um token JWT para simular requisições autenticadas nas rotas protegidas da API.
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);
        $this->headers = ['Authorization' => "Bearer $token"];
    }

    public function test_create_task_success()
    {
        // Testa a criação de uma nova tarefa com dados válidos.
        // Espera resposta 201 (Criado) e uma mensagem de sucesso com a descrição da tarefa.
        $project = Project::factory()->create();

        $data = [
            'description' => 'Nova tarefa teste',
            'project_id' => $project->id,
            'status' => 'Pendente',
        ];

        $response = $this->postJson('/api/tasks', $data, $this->headers);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'status' => 'success',
                'message' => 'Tarefa criada com sucesso',
                'description' => 'Nova tarefa teste',
            ]);
    }

    public function test_create_task_validation_error()
    {
        // Testa o retorno da API ao tentar criar uma tarefa sem fornecer dados obrigatórios.
        // Espera resposta 422 (Erro de Validação) e mensagem apropriada de erro.
        $response = $this->postJson('/api/tasks', [], $this->headers);

        $response->assertStatus(422)
            ->assertJsonFragment([
                'status' => 'error',
                'message' => 'Erro de validação',
            ]);
    }

    public function test_list_tasks()
    {
        // Cria 3 tarefas e testa se a API retorna corretamente a lista com essas tarefas.
        // Espera resposta 200 e que o campo 'data' contenha exatamente 3 tarefas.
        Task::factory()->count(3)->create();

        $response = $this->getJson('/api/tasks', $this->headers);

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => 'success'])
            ->assertJsonCount(3, 'data');
    }

    public function test_show_task()
    {
        // Cria uma tarefa e testa se a API retorna corretamente os detalhes dessa tarefa.
        // Espera resposta 200 e confirmação da descrição da tarefa retornada.
        $task = Task::factory()->create();

        $response = $this->getJson("/api/tasks/{$task->id}", $this->headers);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'status' => 'success',
                'message' => 'Detalhes da tarefa',
                'description' => $task->description
            ]);
    }

    public function test_update_task()
    {
        // Cria uma tarefa e testa a atualização de seus dados (descrição e status).
        // Espera resposta 200 e verificação dos dados atualizados na resposta.
        $task = Task::factory()->create();

        $data = [
            'description' => 'Atualizada',
            'project_id' => $task->project_id,
            'status' => 'Pendente'
        ];

        $response = $this->putJson("/api/tasks/{$task->id}", $data, $this->headers);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'status' => 'success',
                'message' => 'Tarefa atualizada com sucesso',
                'description' => 'Atualizada',
                'status' => 'Pendente'
            ]);
    }

    public function test_delete_task()
    {
        $task = Task::factory()->create();

        $response = $this->deleteJson("/api/tasks/{$task->id}", [], $this->headers);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'status' => 'success',
                'message' => 'Tarefa excluída com sucesso'
            ]);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }


    public function test_delete_task_with_dependents_should_fail()
    {
        // Cria duas tarefas onde uma é predecessora da outra.
        // Testa que a exclusão da tarefa predecessora falha com status 400 e mensagem apropriada.
        // Essa verificação garante integridade entre tarefas relacionadas.
        $task1 = Task::factory()->create();
        $task2 = Task::factory()->create(['predecessor_id' => $task1->id]);

        $response = $this->deleteJson("/api/tasks/{$task1->id}", [], $this->headers);

        $response->assertStatus(400)
            ->assertJsonFragment([
                'status' => 'error',
                'message' => 'Não é possível excluir a tarefa pois ela é predecessora de outra.'
            ]);
    }

    public function test_does_not_allow_completing_dependent_with_pending_predecessor()
    {
        $predecessora = Task::factory()->create(['status' => 'Pendente']);
        $dependente = Task::factory()->create([
            'status' => 'Pendente',
            'predecessor_id' => $predecessora->id
        ]);

        $response = $this->patchJson(route('tasks.updateStatus', $dependente), [
            'status' => 'Concluida'
        ], $this->headers);

        $response->assertStatus(400)
            ->assertJson([
                'status' => 'error',
                'message' => 'Não é possível concluir esta tarefa pois a predecessora ainda não está concluída.'
            ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $dependente->id,
            'status' => 'Pendente',
        ]);
    }

    public function test_does_not_allow_reopening_dependent_with_completed_predecessor()
    {
        $predecessora = Task::factory()->create(['status' => 'Concluida']);
        $dependente = Task::factory()->create(['status' => 'Concluida', 'predecessor_id' => $predecessora->id]);

        $response = $this->patchJson(route('tasks.updateStatus', $predecessora), [
            'status' => 'Pendente'
        ], $this->headers);

        $response->assertStatus(400)
            ->assertJson([
                'status' => 'error',
                'message' => 'Não é possível reabrir esta tarefa pois há dependentes já concluídos.'
            ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $dependente->id,
            'status' => 'Concluida'
        ]);
    }
}

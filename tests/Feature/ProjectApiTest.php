<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProjectApiTest extends TestCase
{

    use RefreshDatabase;

    protected $user;
    protected $headers;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    // Cria um usuário e autentica com JWT para simular requisições autenticadas.
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $token = JWTAuth::fromUser($this->user);
        $this->headers = ['Authorization' => "Bearer $token"];
    }


    public function test_create_project_success()
    {
        $data = [
            'name' => 'Projeto Alpha',
            'description' => 'Descrição do Projeto Alpha',
            'status' => 'Ativo',
            'budget' => 5000,
        ];

        $response = $this->postJson('/api/projects', $data, $this->headers);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'message' => 'Inserido com sucesso',
                'data' => ['name' => 'Projeto Alpha']
            ]);
    }


    public function test_create_project_validation_error()
    {
        // Testa a resposta da API ao tentar criar um projeto sem fornecer dados obrigatórios.
        // Espera-se resposta 422 (erro de validação) e mensagem adequada.
        $response = $this->postJson('/api/projects', [], $this->headers);

        $response->assertStatus(422)
            ->assertJson([
                'status' => 'error',
                'message' => 'Erro de validação',
            ]);
    }


    public function test_get_project_list()
    {
        // Cria 3 projetos fictícios e testa se a API retorna corretamente a lista desses projetos.
        // Verifica se a resposta tem status 200 e contém exatamente 3 itens no campo 'data'.
        Project::factory()->count(3)->create();

        $response = $this->getJson('/api/projects', $this->headers);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Lista de projetos',
            ])
            ->assertJsonCount(3, 'data');
    }


    public function test_update_project()
    {
        $project = Project::factory()->create([
            'name' => 'Projeto Original',
            'user_id' => $this->user->id
        ]);

        $data = [
            'name' => 'Projeto Editado',
            'description' => 'Editado',
            'status' => 'Ativo',
            'budget' => 1000,
        ];

        $response = $this->putJson("/api/projects/{$project->id}", $data, $this->headers);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Atualizado com sucesso',
                'data' => ['name' => 'Projeto Editado']
            ]);
    }


    public function test_delete_project()
    {
        $project = Project::factory()->create([
            'user_id' => $this->user->id
        ]);
    
        $response = $this->deleteJson("/api/projects/{$project->id}", [], $this->headers);
    
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Projeto excluído com sucesso'
            ]);
    
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }
}

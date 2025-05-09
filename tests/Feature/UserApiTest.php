<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User; // Modelo de Usuário
use Tymon\JWTAuth\Facades\JWTAuth; // Para gerar o JWT token se necessário

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa a rota de login com credenciais válidas.
     *
     * @return void
     */
    public function test_login_success()
    {
        // Criando um usuário
        $user = User::factory()->create([
            'email' => 'testuser@example.com',
            'password' => bcrypt('password123'), // Defina a senha corretamente
        ]);

        // Envia requisição GET para a rota de login com as credenciais
        $response = $this->json('GET', '/api/login', [
            'email' => 'testuser@example.com',
            'password' => 'password123',
        ]);

        // Verifica se o retorno é 200 e o token foi gerado
        $response->assertStatus(200)
            ->assertJsonStructure(['token']); // Espera um campo 'token'
    }

    /**
     * Testa a rota de login com credenciais inválidas.
     *
     * @return void
     */
    public function test_login_failure_invalid_credentials()
    {
        // Envia requisição GET para a rota de login com credenciais incorretas
        $response = $this->json('GET', '/api/login', [
            'email' => 'invaliduser@example.com',
            'password' => 'wrongpassword',
        ]);

        // Verifica se a resposta é 401 e o erro esperado
        $response->assertStatus(401)
            ->assertJson([
                'status' => 'error',
                'message' => 'Credenciais inválidas'
            ]);
    }

    /**
     * Testa a rota de login com dados ausentes.
     *
     * @return void
     */
    public function test_login_failure_missing_data()
    {
        // Envia requisição GET para a rota de login sem email ou senha
        $response = $this->json('GET', '/api/login', []);

        // Verifica se a resposta é 401 e o erro esperado
        $response->assertStatus(401)
            ->assertJson([
                'status' => 'error',
                'message' => 'Credenciais inválidas'
            ]);
    }

    /**
     * Testa a rota /api/user com token JWT válido.
     *
     * @return void
     */
    public function test_get_user_with_valid_token()
    {
        // Criar um usuário para autenticar
        $user = User::factory()->create([
            'email' => 'testuser@example.com',
            'password' => bcrypt('password123'),
        ]);

        // Gerar um token JWT para o usuário
        // Realiza o login e obtém o token JWT
        $token = JWTAuth::fromUser($user);

        // Enviar requisição GET para /api/user com o token JWT no header
        $response = $this->json('GET', '/api/user', [], [
            'Authorization' => 'Bearer ' . $token
        ]);

        // Verificar se a resposta é 200 e se os dados do usuário são retornados
        $response->assertStatus(200)
            ->assertJsonFragment([
                'email' => 'testuser@example.com',
            ]);
    }
}

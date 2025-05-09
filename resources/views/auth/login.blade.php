@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="row justify-content-center">
    <div id="alert-container">
        <!-- Exibir a mensagem de erro, se existir -->
        @if (session('error'))
        <div class="alert alert-danger" style="text-align: center;">
            {{ session('error') }}
        </div>
        @endif
    </div>

    <div class="col-md-4" id="login-container">
        <h2 class="mb-4 text-center">Login</h2>

        <form id="login-form">
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required autofocus>
            </div>

            <div class="mb-3">
                <label>Senha</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Entrar</button>
        </form>

        <div class="text-center mt-3">
            <a href="#" id="show-register">Não possui cadastro?</a>
        </div>
    </div>

    <div class="col-md-4" id="register-container" style="display: none;">
        <h2 class="mb-4 text-center">Cadastro</h2>

        <form id="register-form">
            <div class="mb-3">
                <label>Nome</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Senha</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Confirmar Senha</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success w-100">Cadastrar</button>
        </form>

        <div class="text-center mt-3">
            <a href="#" id="show-login">Longin</a>
        </div>
    </div>
</div>


<script>
    // Mostrar formulário de cadastro
    document.getElementById('show-register').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('register-container').style.display = 'block';
        document.getElementById('login-container').style.display = 'none';
    });

    // Mostrar formulário de login
    document.getElementById('show-login').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('register-container').style.display = 'none';
        document.getElementById('login-container').style.display = 'block';
    });

    // Login
    document.getElementById('login-form').addEventListener('submit', async function(e) {
        e.preventDefault();

        const email = this.email.value;
        const password = this.password.value;

        try {
            const response = await axios.get('/autentic', {
                params: {
                    email,
                    password
                },
                withCredentials: true // importante para manter os cookies
            });

            if (response.data.status === 'success') {
                // Guarda token no localStorage (opcional)
                localStorage.setItem('token', response.data.token);

                // Agora armazena o token na sessão PHP
                await axios.post('/store-token', {
                    token: response.data.token
                }, {
                    withCredentials: true
                });

                // Redireciona para a rota protegida
                window.location.href = '/dashboard';
            } else {
                throw new Error(response.data.message || 'Erro desconhecido');
            }

        } catch (error) {
            const msg = error.response?.data?.message || error.message || 'Erro ao fazer login';
            document.getElementById('alert-container').innerHTML = `
            <div class="alert alert-danger" role="alert">
                ${msg}
            </div>`;
        }
    });

    // Cadastro 
    document.getElementById('register-form').addEventListener('submit', async function(e) {
        e.preventDefault();

        const form = new FormData(this);
        const payload = Object.fromEntries(form.entries());

        try {
            const response = await axios.post('/register', payload);

            document.getElementById('alert-container').innerHTML = `
                <div class="alert alert-success" role="alert">
                    Cadastro realizado com sucesso! Agora você pode fazer login.
                </div>`;

            document.getElementById('register-container').style.display = 'none';
            document.getElementById('show-register').style.display = 'block';
            document.getElementById('login-container').style.display = 'block';

        } catch (error) {
            const msg = error.response?.data?.message || 'Erro ao cadastrar';
            document.getElementById('alert-container').innerHTML = `
                <div class="alert alert-danger" role="alert">
                    ${msg}
                </div>`;
        }
    });
</script>


@endsection
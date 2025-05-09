@extends('layouts.app')

@section('title', 'Novo Projeto')

@section('content')
<div class="container">
    <h2 class="mb-4">Criar Novo Projeto</h2>

    <div id="alert-container"></div>

    <form id="create-project-form">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nome do Projeto *</label>
            <input type="text" class="form-control" id="name" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Descrição</label>
            <textarea class="form-control" id="description" rows="3" required></textarea>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status *</label>
            <select class="form-select" id="status" required>
                <option value="Ativo">Ativo</option>
                <option value="Inativo">Inativo</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="budget" class="form-label">Orçamento Disponível</label>
            <input type="number" step="0.01" class="form-control" id="budget" required>
        </div>

        <button type="submit" class="btn btn-success">Salvar</button>
        <!-- <a href="{{ route('projects.index') }}" class="btn btn-secondary">Cancelar</a> -->
        <a href="javascript:history.back()" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script>
    document.getElementById('create-project-form').addEventListener('submit', async function (e) {
        e.preventDefault();

        const token = localStorage.getItem('token');
        const alertContainer = document.getElementById('alert-container');

        try {
            const response = await axios.post('/api/projects', {
                name: document.getElementById('name').value,
                description: document.getElementById('description').value,
                status: document.getElementById('status').value,
                budget: document.getElementById('budget').value,
            }, {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });

            alertContainer.innerHTML = `<div class="alert alert-success">${response.data.message}</div>`;
            setTimeout(() => {
                window.location.href = "/projects";
            }, 1000);

        } catch (error) {
            const msg = error.response?.data?.message || 'Erro ao criar projeto.';
            const details = error.response?.data?.errors ?? {};
            alertContainer.innerHTML = `<div class="alert alert-danger">${msg}<br>${Object.values(details).join('<br>')}</div>`;
        }
    });
</script>
@endsection

@extends('layouts.app')

@section('title', 'Editar Projeto')

@section('content')
<div class="container">
    <h2 class="mb-4">Editar Projeto</h2>

    <div id="alert-container"></div>

    <form id="edit-project-form">
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

        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        <!-- <a href="{{ route('projects.index') }}" class="btn btn-secondary">Cancelar</a> -->
        <a href="javascript:history.back()" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script>
    const token = localStorage.getItem('token');
    const projectId = window.location.pathname.split('/')[2]; // Extrai o ID da URL
    console.log(projectId)

    document.addEventListener('DOMContentLoaded', async () => {
        const alertContainer = document.getElementById('alert-container');

        try {
            const response = await axios.get(`/api/projects/${projectId}`, {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });

            const project = response.data.data;

            document.getElementById('name').value = project.name;
            document.getElementById('description').value = project.description || '';
            document.getElementById('status').value = project.status;
            document.getElementById('budget').value = project.budget ?? '';

        } catch (error) {
            const msg = error.response?.data?.message || 'Erro ao carregar projeto.';
            alertContainer.innerHTML = `<div class="alert alert-danger">${msg}</div>`;
        }
    });

    document.getElementById('edit-project-form').addEventListener('submit', async function (e) {
        e.preventDefault();

        const alertContainer = document.getElementById('alert-container');

        try {
            const response = await axios.put(`/api/projects/${projectId}`, {
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
                // window.history.back();
            }, 1000);

        } catch (error) {
            const msg = error.response?.data?.message || 'Erro ao atualizar projeto.';
            const details = error.response?.data?.errors ?? {};
            alertContainer.innerHTML = `<div class="alert alert-danger">${msg}<br>${Object.values(details).join('<br>')}</div>`;
        }
    });
</script>
@endsection

@extends('layouts.app')

@section('title', 'Nova Tarefa')

@section('content')
<div class="container">
    <h2 class="mb-4">Criar Nova Tarefa</h2>

    <div id="alert-container"></div>

    <form id="create-task-form">
        @csrf

        <div class="mb-3">
            <label for="description" class="form-label">Descrição *</label>
            <input type="text" class="form-control" id="description" required>
        </div>

        <div class="mb-3">
            <label for="start_date" class="form-label">Data de Início</label>
            <input type="date" class="form-control" id="start_date">
        </div>

        <div class="mb-3">
            <label for="end_date" class="form-label">Data de Fim</label>
            <input type="date" class="form-control" id="end_date">
        </div>

        <div class="mb-3">
            <label for="predecessor_id" class="form-label">Tarefa Predecessora (opcional)</label>
            <select class="form-select" id="predecessor_id">
                <option value="">Nenhuma</option>
                @foreach($tasks as $t)
                <option value="{{ $t->id }}" {{ (old('predecessor_id', $task->predecessor_id ?? '') == $t->id) ? 'selected' : '' }}>
                    {{ $t->description }}
                </option>
                @endforeach
            </select>
        </div>

        <input type="hidden" id="project_id" value="{{ request()->route('projectId') }}">

        <button type="submit" class="btn btn-success">Salvar</button>
        <!-- <a href="/projects/{{ request()->route('projectId') }}" class="btn btn-secondary">Cancelar</a> -->
        <a href="javascript:history.back()" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script>
    const token = localStorage.getItem('token');
    const projectId = document.getElementById('project_id').value;

    document.addEventListener('DOMContentLoaded', async () => {
        try {
            // Carrega possíveis tarefas predecessoras
            const response = await axios.get(`/api/projects/${projectId}/tasks`, {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });

            const tasks = response.data.data;
            const select = document.getElementById('predecessor_id');
            tasks.forEach(task => {
                select.innerHTML += `<option value="${task.id}">${task.description}</option>`;
            });

        } catch (error) {
            console.error('Erro ao carregar tarefas predecessoras', error);
        }
    });

    document.getElementById('create-task-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        const alertContainer = document.getElementById('alert-container');

        const data = {
            description: document.getElementById('description').value,
            start_date: document.getElementById('start_date').value,
            end_date: document.getElementById('end_date').value,
            predecessor_id: document.getElementById('predecessor_id').value || null,
            project_id: projectId
        };

        try {
            const response = await axios.post('/api/tasks', data, {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });

            alertContainer.innerHTML = `<div class="alert alert-success">${response.data.message}</div>`;
            setTimeout(() => {
                window.location.href = `/projects/${projectId}`;
                // window.history.back();
            }, 1000);
        } catch (error) {
            const msg = error.response?.data?.message || 'Erro ao criar tarefa.';
            const details = error.response?.data?.errors ?? {};
            alertContainer.innerHTML = `<div class="alert alert-danger">${msg}<br>${Object.values(details).join('<br>')}</div>`;
        }
    });
</script>
@endsection
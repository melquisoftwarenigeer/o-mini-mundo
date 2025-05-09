@extends('layouts.app')

@section('title', 'Detalhes da Tarefa')

@section('content')
<div class="container">
    <h2 class="mb-4">Detalhes da Tarefa</h2>

    <div id="alert-container"></div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title" id="task-description">Descrição da Tarefa</h5>
            <p class="card-text"><strong>Status:</strong> <span id="task-status"></span></p>
            <p class="card-text"><strong>Data de Início:</strong> <span id="task-start_date"></span></p>
            <p class="card-text"><strong>Data de Fim:</strong> <span id="task-end_date"></span></p>
        </div>
    </div>

    <div class="mt-4">
        <a href="/projects/{{ request()->route('projectId') }}/tasks/{{ request()->route('id') }}/edit" class="btn btn-warning">Editar</a>
        <!-- <a href="/projects/{{ request()->route('projectId') }}/tasks" class="btn btn-secondary">Voltar</a> -->
        <a href="javascript:history.back()" class="btn btn-secondary">Voltar</a>
    </div>
</div>

<script>
    const token = localStorage.getItem('token');
    const taskId = window.location.pathname.split('/').pop();
    const projectId = window.location.pathname.split('/')[3]; // Ajuste para pegar o ID do projeto

    document.addEventListener('DOMContentLoaded', async () => {
        const alertContainer = document.getElementById('alert-container');

        try {
            const response = await axios.get(`/api/tasks/${taskId}`, {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });

            const task = response.data.data;

            document.getElementById('task-description').innerText = task.description;
            document.getElementById('task-status').innerText = task.status;
            document.getElementById('task-start_date').innerText = task.start_date ?? '-';
            document.getElementById('task-end_date').innerText = task.end_date ?? '-';

        } catch (error) {
            const msg = error.response?.data?.message || 'Erro ao carregar tarefa.';
            alertContainer.innerHTML = `<div class="alert alert-danger">${msg}</div>`;
        }
    });
</script>
@endsection
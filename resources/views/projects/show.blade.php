@extends('layouts.app')

@section('title', 'Detalhes do Projeto')

@section('content')
<div class="container">
    <h2 class="mb-4">Detalhes do Projeto</h2>

    <div id="alert-container"></div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title" id="project-name">{{ $project->name }}</h5>
            <p class="card-text"><strong>Status:</strong> <span id="project-status">{{ $project->status }}</span></p>
            <p class="card-text"><strong>Descrição:</strong> <span id="project-description">{{ $project->description ?? '-' }}</span></p>
            <p class="card-text"><strong>Orçamento:</strong> R$ <span id="project-budget">{{ $project->budget ?? '0.00' }}</span></p>
        </div>
    </div>

    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h4>Tarefas do Projeto</h4>
            <a href="{{ route('tasks.create', $project->id) }}" class="btn btn-sm btn-success">Nova Tarefa</a>
        </div>

        <!-- Barra de progresso -->
        <div class="progress mb-3" style="height: 25px;">
            <div id="progress-bar" class="progress-bar bg-secondary" role="progressbar"
                style="width: 0%; min-width: 16px; text-align: center;">
                0%
            </div>
        </div>

        <!-- Tabela de tarefas -->
        <table class="table table-bordered table-hover" id="tasks-table" style="display: none;">
            <thead class="table-light">
                <tr>
                    <th>Descrição</th>
                    <th>Status</th>
                    <th>Início</th>
                    <th>Fim</th>
                    <th>Predecessora</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody id="tasks-body">
                <tr>
                    <td colspan="6" class="text-center">Nenhum tarefa encontrado.</td>
                </tr>
            </tbody>
        </table>
    </div>

    <a href="/projects/{{ $project->id }}/edit" class="btn btn-warning">Editar Projeto</a>
    <!-- <a href="{{ route('projects.index') }}" class="btn btn-secondary">Voltar</a> -->
    <a href="javascript:history.back()" class="btn btn-secondary">Voltar</a>
    <input type="hidden" name="" id="projectid" value="{{ $project->id }}">
</div>

<script>
    const token = localStorage.getItem('token');
    projectId = document.getElementById('projectid').value;
    const alertContainer = document.getElementById('alert-container');

    console.log(projectId);

    document.addEventListener('DOMContentLoaded', async () => {
        try {
            const response = await axios.get('/api/tasks?project_id=' + projectId, {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });

            const tasks = response.data.data;
            renderTasks(tasks);
            updateProgress(tasks);

        } catch (error) {
            alertContainer.innerHTML = `<div class="alert alert-danger">Erro ao carregar tarefas.</div>`;
        }
    });

    function renderTasks(tasks) {
        const tbody = document.getElementById('tasks-body');
        const table = document.getElementById('tasks-table');
        tbody.innerHTML = '';

        if (tasks.length === 0) {
            tbody.innerHTML = `<tr><td colspan="6" class="text-center">Nenhuma tarefa encontrada.</td></tr>`;
        } else {
            tasks.forEach(task => {
                console.log(task.project_id)
                const statusBtn = task.status === 'Concluida' ?
                    `<button class="btn btn-sm btn-outline-secondary" onclick="toggleStatus(${task.id}, 'Pendente')">Reabrir</button>` :
                    `<button class="btn btn-sm btn-success" onclick="toggleStatus(${task.id}, 'Concluida')">Concluir</button>`;

                tbody.innerHTML += `
                    <tr>
                        <td>${task.description}</td>
                        <td>${task.status}</td>
                        <td>${task.start_date.split('T')[0] ?? '-'}</td>
                        <td>${task.end_date.split('T')[0] ?? '-'}</td>
                        <td>${task.predecessor?.description ?? '-'}</td>
                        <td>
                            ${statusBtn}
                            <a href="/projects/${task.project_id}/tasks/${task.id}/edit" class="btn btn-sm btn-warning">Editar</a>
                            <button class="btn btn-sm btn-danger" onclick="deleteTask(${task.id})">Excluir</button>
                        </td>
                    </tr>
                `;
            });

            table.style.display = 'table';
        }
    }

    function updateProgress(tasks) {
        const total = tasks.length;
        const completed = tasks.filter(t => t.status === 'Concluida').length;
        const percent = total > 0 ? Math.round((completed / total) * 100) : 0;

        const bar = document.getElementById('progress-bar');
        bar.style.width = percent + '%';
        bar.innerText = percent + '%';

        // Limpa classes de cor anteriores
        bar.classList.remove('bg-success', 'bg-info', 'bg-secondary');

        // Aplica a cor de acordo com o progresso
        if (percent === 100) {
            bar.classList.add('bg-success');
        } else if (percent === 0) {
            bar.classList.add('bg-secondary'); // cinza, visível mesmo em 0%
        } else {
            bar.classList.add('bg-info');
        }
    }

    async function toggleStatus(taskId, newStatus) {
        try {
            await axios.patch(`/tasks/${taskId}/status`, {
                status: newStatus
            }, {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });

            location.reload();
        } catch (error) {
            const msg = error.response?.data?.message || 'Erro ao alterar status.';
            alertContainer.innerHTML = `<div class="alert alert-danger">${msg}</div>`;
        }
    }

    async function deleteTask(taskId) {
        if (!confirm('Deseja realmente excluir esta tarefa?')) return;

        try {
            await axios.delete(`/api/tasks/${taskId}`, {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });

            location.reload();
        } catch (error) {
            const msg = error.response?.data?.message || 'Erro ao excluir tarefa.';
            alertContainer.innerHTML = `<div class="alert alert-danger">${msg}</div>`;
        }
    }
</script>
@endsection
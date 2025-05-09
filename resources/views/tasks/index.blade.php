@extends('layouts.app')

@section('title', 'Tarefas do Projeto')

@section('content')
<div class="container">
    <h2 class="mb-4">
        {{ request()->is('projects/*') ? 'Tarefas do Projeto' : 'Todas as Tarefas' }}
    </h2>

    <div id="alert-container"></div>
    <div id="loading" class="text-center text-muted mb-3">🔄 Carregando Tarefas...</div>

    <!-- Filtro -->
    <form class="row g-3 mb-4" id="task-filter-form">
        <div class="col-md-4">
            <input type="text" class="form-control" id="filter-description" placeholder="Filtrar por descrição">
        </div>
        <div class="col-md-3">
            <select class="form-select" id="filter-status">
                <option value="">Todos os status</option>
                <option value="Pendente">Pendente</option>
                <option value="Em Andamento">Em Andamento</option>
                <option value="Concluida">Concluída</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-outline-secondary w-100" onclick="resetFilters()">Limpar</button>
        </div>
        <div class="col-md-3 text-end">
            <!-- <a href="{{ request()->is('projects/*') ? url()->current() . '/create' : '#' }}" class="btn btn-primary w-100">Nova Tarefa</a> -->
        </div>
    </form>

    <!-- Tabela -->
    <table class="table table-bordered table-hover" id="task-table" style="display: none;">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Status</th>
                <th>Periodo</th>
                <th>Projeto</th>
                <th>Predecessora</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody id="task-body"></tbody>
    </table>
    <input type="hidden" name="userlogado" id="userlogado" value="{{ auth()->user()->id }}">
    <!-- <a href="{{ route('dashboard') }}" class="btn btn-secondary">Voltar</a> -->
    <a href="javascript:history.back()" class="btn btn-secondary">Voltar</a>
</div>


<script>
    const token = localStorage.getItem('token');
    const pathParts = window.location.pathname.split('/');
    const projectId = pathParts[1] === 'projects' ? pathParts[2] : null;
    const loading = document.getElementById('loading');

    let allTasks = [];

    document.addEventListener('DOMContentLoaded', async () => {
        const table = document.getElementById('task-table');
        const alertContainer = document.getElementById('alert-container');

        try {
            const url = projectId ?
                `/api/projects/${projectId}/tasks` :
                `/api/tasks`; // endpoint para todas as tarefas

            const response = await axios.get(url, {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });

            allTasks = response.data;
            loading.style.display = 'none';

            // console.log(allTasks);
            renderTasks(allTasks);
            table.style.display = 'table';
        } catch (error) {
            loading.style.display = 'none';
            const msg = error.response?.data?.message || 'Erro ao carregar tarefas.';
            alertContainer.innerHTML = `<div class="alert alert-danger">${msg}</div>`;
        }

        document.getElementById('filter-description').addEventListener('input', filterTasks);
        document.getElementById('filter-status').addEventListener('change', filterTasks);
    });

    function renderTasks(tasks) {
        const tbody = document.getElementById('task-body');
        const data = tasks.data ? tasks.data : tasks;
        const useronline = document.getElementById('userlogado');
        tbody.innerHTML = '';

        if (data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="6" class="text-center">Nenhuma tarefa encontrada.</td></tr>`;
            return;
        }

        // if (data[0].project.user_id != useronline.value) {
        //     tbody.innerHTML = `<tr><td colspan="6" class="text-center">Nenhuma tarefa encontrada.</td></tr>`;
        //     return;
        // }

        // <button class="btn btn-sm btn-success" onclick="toggleStatus(${task.id})">Alterar Status</button>
        data.forEach(task => {

            if (task.project.user_id != useronline.value) {
                return; // Substitui o 'continue'
            }

            tbody.innerHTML += `
                <tr>
                    <td>${task.description}</td>
                    <td>${task.status}</td>
                    <td>${task.start_date.split('T')[0] ?? '-'} -> ${task.end_date.split('T')[0] ?? '-'}</td>
                    <td>${task.project.description}</td>
                    <td>${task.predecessor_id ?? '-'}</td>
                    <td>                       
                        <a href="/projects/${task.project.id}/tasks/${task.id}/edit" class="btn btn-sm btn-warning">Editar</a>
                        <button class="btn btn-sm btn-danger" onclick="deleteTask(${task.id})">Excluir</button>
                    </td>
                </tr> 
            `;
        });

        if (tbody.innerHTML == '') {
            tbody.innerHTML = `<tr><td colspan="6" class="text-center">Nenhuma tarefa encontrada.</td></tr>`;
        }
    }

    function filterTasks() {
        const desc = document.getElementById('filter-description').value.toLowerCase();
        const status = document.getElementById('filter-status').value;

        console.log(allTasks);
        const filtered = allTasks.data.filter(t =>
            t.description.toLowerCase().includes(desc) &&
            (status === '' || t.status === status)
        );

        renderTasks(filtered);
    }

    function resetFilters() {
        document.getElementById('filter-description').value = '';
        document.getElementById('filter-status').value = '';
        renderTasks(allTasks);
    }

    async function toggleStatus(id) {
        try {
            await axios.patch(`/api/tasks/${id}/toggle-status`, {}, {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });
            alert('Status atualizado!');
            location.reload();
        } catch (e) {
            alert('Erro ao atualizar status');
        }
    }

    async function deleteTask(id) {
        if (!confirm('Deseja realmente excluir a tarefa?')) return;

        try {
            await axios.delete(`/api/tasks/${id}`, {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });
            alert('Tarefa excluída!');
            location.reload();
        } catch (e) {
            alert(e.response?.data?.message || 'Erro ao excluir tarefa');
        }
    }
</script>

@endsection
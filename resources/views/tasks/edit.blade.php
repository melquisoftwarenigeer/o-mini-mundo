@extends('layouts.app')

@section('title', 'Editar Tarefa')

@section('content')
<div class="container">
    <h2 class="mb-4">Editar Tarefa</h2>

    <div id="alert-container"></div>
  
    <form id="edit-task-form">
        @csrf
       
        <input type="hidden" id="project_id" value=" {{$task->project_id}}">
        <input type="hidden" id="task_id" value="{{$task->id}}">

        <div class="mb-3">
            <label for="description" class="form-label">Descrição *</label>
            <textarea class="form-control" id="description" rows="3" required></textarea>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status *</label>
            <select class="form-select" id="status" required>
                <option value="Pendente">Pendente</option>
                <option value="Em Andamento">Em Andamento</option>
                <option value="Concluida">Concluída</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="start_date" class="form-label">Data de Início</label>
            <input type="date" class="form-control" id="start_date">
        </div>

        <div class="mb-3">
            <label for="end_date" class="form-label">Data de Fim</label>
            <input type="date" class="form-control" id="end_date">
        </div>

        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        <!-- <a href="/projects/{{ request('projectId') }}" class="btn btn-secondary">Cancelar</a> -->
        <a href="javascript:history.back()" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script>
    const token = localStorage.getItem('token');
    const taskId = document.getElementById('task_id').value;
    const projectId = document.getElementById('project_id').value;

    document.addEventListener('DOMContentLoaded', async () => {
        const alertContainer = document.getElementById('alert-container');

        try {
            const response = await axios.get(`/api/tasks/${taskId}`, {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });

            const task = response.data.data;
            console.log(task);
            document.getElementById('description').value = task.description;
            document.getElementById('status').value = task.status;
            document.getElementById('start_date').value = task.start_date ? task.start_date.split('T')[0] : '';
            document.getElementById('end_date').value = task.end_date ? task.end_date.split('T')[0] : '';

        } catch (error) {
            const msg = error.response?.data?.message || 'Erro ao carregar tarefa.';
            alertContainer.innerHTML = `<div class="alert alert-danger">${msg}</div>`;
        }
    });

    document.getElementById('edit-task-form').addEventListener('submit', async function(e) {
        e.preventDefault();

        const alertContainer = document.getElementById('alert-container');

        try {
            const response = await axios.put(`/api/tasks/${taskId}`, {
                description: document.getElementById('description').value,
                status: document.getElementById('status').value,
                start_date: document.getElementById('start_date').value,
                end_date: document.getElementById('end_date').value,
                project_id: document.getElementById('project_id').value
            }, {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });

            alertContainer.innerHTML = `<div class="alert alert-success">${response.data.message}</div>`;
            setTimeout(() => {
                const projectId = document.getElementById('project_id').value.trim();                
                window.location.href = `/projects/${projectId}`;
            }, 1000);

        } catch (error) {
            const msg = error.response?.data?.message || 'Erro ao atualizar tarefa.';
            const details = error.response?.data?.errors ?? {};
            alertContainer.innerHTML = `<div class="alert alert-danger">${msg}<br>${Object.values(details).join('<br>')}</div>`;
        }
    });
</script>
@endsection
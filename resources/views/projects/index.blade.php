@extends('layouts.app')

@section('title', 'Projetos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Projetos</h2>
    <a href="/projects/create" class="btn btn-primary">Novo Projeto</a>
</div>

<div id="alert-container"></div>
<div id="loading" class="text-center text-muted mb-3">🔄 Carregando projetos...</div>

<!-- Filtro de busca -->
<div class="card mb-4">
    <div class="card-body">
        <form id="filter-form" class="row g-3">
            <div class="col-md-6">
                <label for="filter-name" class="form-label">Filtrar por Nome</label>
                <input type="text" class="form-control" id="filter-name" placeholder="Digite parte do nome...">
            </div>
            <div class="col-md-4">
                <label for="filter-status" class="form-label">Status</label>
                <select class="form-select" id="filter-status">
                    <option value="">Todos</option>
                    <option value="Ativo">Ativo</option>
                    <option value="Inativo">Inativo</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-outline-secondary w-100" onclick="resetFilter()">Limpar</button>
            </div>
        </form>
    </div>
</div>
<!-- Filtro de busca -->

<table class="table table-bordered table-hover" style="display:none;" id="project-table">
    <thead class="table-light">
        <tr>
            <th>Nome</th>
            <th>Status</th>
            <th>Orçamento</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody id="project-table-body"></tbody>
</table>

<input type="hidden" name="userlogado" id="userlogado" value="{{ auth()->user()->id }}">

<!-- <a href="{{ route('dashboard') }}" class="btn btn-secondary">Voltar</a> -->
<a href="javascript:history.back()" class="btn btn-secondary">Voltar</a>
<script>
    document.addEventListener('DOMContentLoaded', async () => {
        const token = localStorage.getItem('token');
        const alertContainer = document.getElementById('alert-container');
        const table = document.getElementById('project-table');
        const loading = document.getElementById('loading');
        const tableBody = document.getElementById('project-table-body');
        allProjectsexterno = [];

        try {
            const response = await axios.get('/api/projects', {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });

            allProjects = response.data;
            loading.style.display = 'none';
            table.style.display = 'table';


            allProjectsexterno = allProjects.data;

            renderProjects(allProjects.data);

            // Filtro em tempo real
            document.getElementById('filter-name').addEventListener('input', filterProjects);
            document.getElementById('filter-status').addEventListener('change', filterProjects);

        } catch (error) {
            loading.style.display = 'none';
            const msg = error.response?.data?.message || 'Erro ao carregar projetos.';
            alertContainer.innerHTML = `<div class="alert alert-danger">${msg}</div>`;
        }
    });

    async function deleteProject(id) {
        if (!confirm('Tem certeza que deseja excluir este projeto?')) return;

        const token = localStorage.getItem('token');
        const alertContainer = document.getElementById('alert-container');

        try {
            await axios.delete(`/api/projects/${id}`, {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });

            alertContainer.innerHTML = `<div class="alert alert-success">Projeto excluído com sucesso.</div>`;
            setTimeout(() => location.reload(), 1000);

        } catch (error) {
            const msg = error.response?.data?.message || 'Erro ao excluir projeto.';
            alertContainer.innerHTML = `<div class="alert alert-danger">${msg}</div>`;
        }
    }



    function renderProjects(projects) {
        const tableBody = document.getElementById('project-table-body');
        const useronline = document.getElementById('userlogado');
        tableBody.innerHTML = '';



        if (projects.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="4" class="text-center">Nenhum projeto encontrado.</td></tr>`;
            return;
        }

        // if (projects[0].user_id != useronline.value) {
        //     tableBody.innerHTML = `<tr><td colspan="4" class="text-center">Nenhum projeto encontrado.</td></tr>`;
        //     return;
        // }

        projects.forEach(project => {

            if (project.user_id != useronline.value) {
                return; // Substitui o 'continue'
            }

            tableBody.innerHTML += `
            <tr>
                <td>
                <div class="tooltip-container">
                    <a href="/projects/${project.id}" class="text-decoration-none" onmouseover="hideTooltip(this)">
                    ${project.name}
                    </a>
                    <div class="tooltip-text">Clique para ver detalhes</div>
                </div>
                </td>
                <td>${project.status}</td>
                <td>R$ ${project.budget ?? '-'}</td>
                <td>
                    <a href="/projects/${project.id}/edit" class="btn btn-sm btn-warning">Editar</a>
                    <button onclick="deleteProject(${project.id})" class="btn btn-sm btn-danger">Excluir</button>
                </td>
            </tr>
            `;
        });

        if (tableBody.innerHTML == '') {
            tableBody.innerHTML = `<tr><td colspan="4" class="text-center">Nenhum projeto encontrado.</td></tr>`;
        }
    }

    function filterProjects() {
        const nameFilter = document.getElementById('filter-name').value.toLowerCase();
        const statusFilter = document.getElementById('filter-status').value;

        const filtered = allProjectsexterno.filter(project => {
            const matchesName = project.name.toLowerCase().includes(nameFilter);
            const matchesStatus = statusFilter === '' || project.status === statusFilter;
            return matchesName && matchesStatus;
        });

        renderProjects(filtered);
    }

    function resetFilter() {
        document.getElementById('filter-name').value = '';
        document.getElementById('filter-status').value = '';
        renderProjects(allProjectsexterno);
    }

    function hideTooltip(linkElement) {
        const tooltip = linkElement.parentElement.querySelector('.tooltip-text');
        if (tooltip) {
            tooltip.classList.add('tooltip-hidden');
            linkElement.onmouseover = null;
        }
    }
</script>

<style>
    .tooltip-container {
        position: relative;
        display: inline-block;
    }

    .tooltip-text {
        visibility: visible;
        opacity: .8;
        background-color: #333;
        color: #fff;
        font-size: 12px;
        text-align: left;
        border-radius: 8px;
        padding: 2px 4px;
        position: absolute;
        z-index: 1;
        bottom: 150%;
        left: 30;
        transition: opacity 2s ease;
        white-space: nowrap;
    }

    .tooltip-text::after {
        content: "";
        position: absolute;
        top: 100%;
        left: 50px;
        /* seta mais para o início também */
        border-width: 3px;
        border-style: solid;
        border-color: #333 transparent transparent transparent;
    }

    .tooltip-hidden {
        opacity: 0;
        visibility: hidden;
    }
</style>

@endsection
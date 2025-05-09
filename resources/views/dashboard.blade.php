@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="row">
    <div class="col-12">
        <h2 class="mb-4">Painel de Controle</h2>
    </div>

    <div class="col-md-6">
        <div class="card text-bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Projetos Ativos</h5>
                <p class="card-text">Veja a lista de projetos em andamento.</p>
                <a href="/projects" class="btn btn-light">Ver Projetos</a>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card text-bg-secondary mb-3">
            <div class="card-body">
                <h5 class="card-title">Tarefas</h5>
                <p class="card-text">Gerencie as tarefas de cada projeto.</p>
                <a href="/tasks" class="btn btn-light">Ver Tarefas</a>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')

@endsection
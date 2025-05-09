<!-- resources/views/components/header.blade.php -->
<div class="container-fluid p-3" style="background-color: rgba(13, 110, 253, 0.85); color: white; border-radius: 0.5rem; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
    <div class="d-flex justify-content-between align-items-center">
        <!-- Seção do nome do usuário -->
        <div class="d-flex align-items-center">
            <a href="{{ route('dashboard') }}" class="btn btn-secondary" style="opacity: .7;border-radius: 20px; --bs-btn-bg: #7d6c6c66;">🏠 Início</a>
            <i class="bi bi-person-circle me-2" style="font-size: 1.5rem;"> </i>
            <h6 class="m-0">Bem-vindo, {{ auth()->user()->name ?? '' }}</h6>
        </div>

        <!-- Seção do logout como ícone -->
        <a title="Sair" id="logout-button" style="cursor: pointer;">Logout</a>
    </div>
</div>

<!-- Alert container -->
<div id="alert-container"></div>
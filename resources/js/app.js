import './bootstrap';
import { setupLogoutButton } from './logout'; // Importa a função de logout

// Configura o botão de logout quando a página for carregada
document.addEventListener('DOMContentLoaded', () => {
    setupLogoutButton(); // Configura o evento de logout
});
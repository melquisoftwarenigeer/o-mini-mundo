export async function logout() {
    try {
        // Faz a requisição para logout
        const response = await axios.post('/logout', {}, {
            withCredentials: true // Necessário para manter os cookies
        });

        if (response.data.status === 'success') {
            // Limpa o token do localStorage
            localStorage.removeItem('token');

            // Redireciona para a página de login ou qualquer outra página
            window.location.href = '/';
        } else {
            throw new Error(response.data.message || 'Erro ao fazer logout');
        }
    } catch (error) {
        const msg = error.response?.data?.message || error.message || 'Erro ao fazer logout';
        document.getElementById('alert-container').innerHTML = `
            <div class="alert alert-danger" role="alert">
                ${msg}
            </div>`;
    }
}

// Configuração do evento de click no botão de logout
export function setupLogoutButton() {
    const logoutButton = document.getElementById('logout-button');
    if (logoutButton) {
        logoutButton.addEventListener('click', async function (e) {
            e.preventDefault();
            await logout();
        });
    }
}
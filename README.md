<p align="center">
  <img src="https://github.com/user-attachments/assets/bde9e4c8-70a1-46a3-ac5f-d05d3ed93477" alt="Codificando..." />
</p>

# 🚀 Mini Mundo

Projeto de laboratório para testes e validações técnicas com Laravel + Docker + PostgreSQL.

---

## 📌 Sobre o Projeto

O **Mini Mundo** é uma aplicação backend construída em Laravel, idealizada para fins educacionais e de avaliação técnica. Ele simula um ambiente real de desenvolvimento com foco em:

- Boas práticas de versionamento
- Testes automatizados
- Deploy contínuo com Docker e GitHub Actions
- Integração com banco de dados PostgreSQL
- Autenticação via JWT
- Gerenciamento de dependências com Composer e NPM

---

## 🛠️ Tecnologias Utilizadas

- ⚙️ **Laravel 10+**
- 🐘 **PostgreSQL**
- 🐳 **Docker + Docker Compose**
- 📦 **Composer** (PHP)
- ⚡ **Vite** (compilação frontend)
- 🔐 **JWT Authentication**
- 🧪 **PHPUnit** (testes)
- 🚀 **CI/CD com GitHub Actions**
- 📤 **Publicação no Docker Hub**

---

## 🧱 Arquitetura dos Containers

┌──────────────────────────────────────────────────┐
│                  NGINX (porta 80)                │
│        (Reverse Proxy + Servidor HTTP)           │
└──────────────┬───────────────────────────────────┘
               │
        ┌──────▼─────┐     ┌────────────────────┐
        │  PHP-FPM   │     │    PostgreSQL      │
        │ (Laravel)  │     │ (porta 5432:15432) │
        └────────────┘     └────────────────────┘


🚀 Como rodar o projeto localmente
## 1️⃣ Clone o projeto

    -git clone https://github.com/melquisoftwarenigeer/o-mini-mundo.git
    -cd o-mini-mundo

## 2️⃣ Instale as dependências Laravel

    -cp .env.example .env
    -composer install
    -npm install
    -php artisan key:generate
    -php artisan jwt:secret

## 3️⃣.1️⃣ Configure o banco de dados (PostgreSQL / Docker) OP1
 
    DB_CONNECTION=pgsql
    DB_HOST=postgres  
    DB_PORT=5432 
    DB_DATABASE=LaravelPipes
    DB_USERNAME=melqui
    DB_PASSWORD=123456

    |- DB_HOST=localhost  
    |- DB_PORT=15432
    Obs.: Acima descrito dados de host e port, para fins de teste           
          exemplo no SGBD com a imagem do docker rodando.         
    
## 3️⃣.2️⃣ Configure o banco de dados (PostgreSQL / Laravel) OP2
 
    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=LaravelPipe
    DB_USERNAME=postgres
    DB_PASSWORD=123456

## 5️⃣ Deseja Subir o Servidor com Docker? OP1


## 5️⃣ Deseja Subir o Servidor com Docker? OP1

    Arquivos Docker já configurados na aplicação em: 📁
    ├──docker-compose.yml 
    ├──/dockerfiles 
    
    Execute em 2 terminais no seu editor de código para o FrontEnd e BackEnd 🧑 

    🖥️ Terminal 1 — Backend 
      1-docker compose up -d (Para montar a imagem. A primeiara execução pode demorar um pouco)
      2-docker ps            (Para validar se a imagem foi montada)    

    🖥️ Terminal 2 — Frontend 
      3-npm run dev (VITE)
      4-http://localhost (Acessar aplicação)

      Obs. Ao abrir a url
         Aguarde o FrontEnd renderizar totalmente para carregar depenências do Axios.

## 6️⃣ Deseja Subir o Servidor com Laravel Artisan? OP2

    Execute em 2 terminais no seu editor de código, para o FrontEnd e BackEnd 🧑

    🖥️ Terminal 1 — Backend 
     1-php artisan serve 
    🖥️ Terminal 2 — Frontend 
      2-npm run dev (VITE)
      3-http://127.0.0.1:8000 (Acessar aplicação, verificando 
                               qual endereço server foi disponibilizado pelo Laravel)
    
    Obs. Ao abrir a url
         Aguarde o FrontEnd renderizar totalmente para carregar depenências do Axios.

## ✍️ Autor
    
    Melquisedeque Bispo de Jesus
    Este projeto é mantido como parte de um portfólio técnico e pode ser usado em processos seletivos ou provas práticas.

## 📄 Licença
  
    Este projeto é distribuído sob a Licença MIT
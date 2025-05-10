
<p align="center">
  <img src="https://github.com/user-attachments/assets/bde9e4c8-70a1-46a3-ac5f-d05d3ed93477" alt="Codificando..." />
</p>


# 🚀 Mini Mundo - Projeto de Laboratório para Testes e Avaliações Técnicas  

## 📌 Sobre o Projeto  

O **Mini Mundo** é um projeto de laboratório destinado a testes e implementações de validação técnica para seleção de desenvolvedores. Ele permite avaliar candidatos por meio da implementação de **issues específicas**, garantindo que sigam boas práticas de desenvolvimento, versionamento e deploy contínuo.  

## 🔥 Tecnologias Utilizadas  

    🔐 Autenticação com JWT

    🐳 (Containerização do projeto)  

    ⚡ (Automação de build e deploy)  CI/CD

    📂 (Organização do versionamento)

    📦 (Registro das imagens)  **Docker Hub** 

    🛠️ NodeJs Vite do Laravel

🚀 Como rodar o projeto localmente
## 1️⃣ Clone o projeto

    -git clone https://github.com/melquisoftwarenigeer/o-mini-mundo.git
    -cd pastadoprojeto\

## 2️⃣ Instale as dependências Laravel

    -cp .env.example .env
    -composer install
    -npm install
    -php artisan key:generate
    -php artisan jwt:secret

## 3️⃣ Configure o banco de dados (PostgreSQL)

    -No arquivo .env, configure com os dados do seu PostgreSQL
      Certifique que você criou o banco no seu SGBD

    -Se precisar testar na sua aplicação a conexão com banco de dados foi bem sucedida, siga esse passo no terminal:
      php artisan tinker
      DB::connection()->getPdo();
      exit

    -Rode as migrações:
      php artisan migrate:refresh

## 4️⃣ Deseja Subir o Servidor com Docker?

   - Certifique-se de que a porta 5432 esteja livre (PostgreSQL)

    Arquivos Docker já prontos e configurados: 📁
    ├──docker-compose.yml 
    ├──/dockerfiles 
    
    Execute 🧑 
    🖥️ Terminal 1 — Backend 
      -docker compose up -d
    🖥️ Terminal 2 — Frontend (Vite)
      -npm run dev (VITE)

## 5️⃣ Deseja Subir o Servidor com Laravel Artisan?

    🖥️ Terminal 1 — Backend 
      -php artisan serve 
    🖥️ Terminal 2 — Frontend (Vite)
      -npm run dev (VITE)
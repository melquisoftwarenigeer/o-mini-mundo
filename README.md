<p align="center">
  <img src="https://github.com/user-attachments/assets/bde9e4c8-70a1-46a3-ac5f-d05d3ed93477" alt="Codificando..." />
</p>


# 🚀 Mini Mundo - Projeto de Laboratório para Testes e Avaliações Técnicas  

## 📌 Sobre o Projeto  

O **Mini Mundo** é um projeto de laboratório destinado a testes e implementações de validação técnica para seleção de desenvolvedores. Ele permite avaliar candidatos por meio da implementação de **issues específicas**, garantindo que sigam boas práticas de desenvolvimento, versionamento e deploy contínuo.

## 🛠️ Tecnologias Utilizadas

- ⚙️ **Laravel**
- 🐘 **PostgreSQL**
- 🐳 **Docker + Docker Compose + DockerHub**
- 📦 **Composer** (PHP)
- ⚡ **Vite** (compilação frontend)
- 🔐 **JWT Authentication** (Autenticação Segura)
- 🧪 **PHPUnit** (testes)
- 🚀 **CI/CD com GitHub Actions**
- 📤 **Publicação no Docker Hub**

---

## 🧱 Arquitetura do Container

  # 📦 Build e Push da Imagem Docker:

      -Autenticação Login no Docker Hub	Usando secrets configurados no GitHub
      -(DOCKER_USERNAME e DOCKER_PASSWORD).
    
  # 📦 Build da Imagem Docker: 
          
      -A imagem da aplicação é construída com base em um Dockerfile localizado em dockerfiles/php 

  # ⚡ Push da Imagem para o Docker Hub:

      -Após o build, a imagem é enviada para o repositório melquidocker/o-mini-mundo.

---

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

---

## 3️⃣ Configure o banco de dados (PostgreSQL / Docker) 
 
    DB_CONNECTION=pgsql
    DB_HOST=postgres  
    DB_PORT=5432 
    DB_DATABASE=bdteste
    DB_USERNAME=melqui
    DB_PASSWORD=123456

    -HOST=localhost  
    -PORT=15432
    -Obs.: Acima dados de host e port, para fins de teste           
           SGBD com a imagem do docker rodando. 

## Servidor com Docker 

    Arquivos Docker já configurados na aplicação em: 📁
    ├──docker-compose.yml 
    ├──/dockerfiles 
    
    Execute em 2 terminais no seu editor de código para o FrontEnd e BackEnd 🧑 

    -🖥️ Terminal 1 — Backend 
    -1-Docker instalado e em funcionamento em sua máquina.
    -2-docker compose up -d (Para montar a imagem)
    -3-docker ps            (Para validar se a imagem foi montada)    
    -4-docker compose exec app php artisan migrate

    -🖥️ Terminal 2 — Frontend 
    -1-npm run dev (VITE)
    -2-http://localhost (Acessar aplicação)

      Obs. Ao abrir a url
         Aguarde o FrontEnd renderizar totalmente para carregar depenências do Axios.   
---     
    
## 4️⃣ Configure o banco de dados (PostgreSQL / Laravel)
 
    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=bdteste
    DB_USERNAME=postgres
    DB_PASSWORD=123456

## Servidor com Laravel 

    Execute em 2 terminais no seu editor de código, para o FrontEnd e BackEnd 🧑

    -🖥️ Terminal 1 — Backend
    -1-php artisan migrate (Certifique de que criou o banco de dados pelo SGBD) 
    -2-php artisan serve 

    -🖥️ Terminal 2 — Frontend 
    -1-npm run dev (VITE)
    -2-http://127.0.0.1:8000 (Acessar aplicação, com server do Laravel)
    
    Obs. Ao abrir a url
         Aguarde o FrontEnd renderizar totalmente para carregar depenências do Axios.

---

## 5️⃣ 🧪 Como utilizar a imagem Docker o-mini-mundo -- pull do DockerHub

    -Esta imagem Docker foi criada para facilitar a execução do projeto Mini Mundo em contêineres, ela esta em integração continua a cada pull request no GitHub Action. 
    
    -Siga os passos abaixo se quiser utilizá-la pelo DockerHub em seu ambiente local.

## 🔧 Pré-requisitos

    -Docker instalado e em funcionamento em sua máquina.
    -Acesso à internet para baixar a imagem do Docker Hub.

## 🚀 Passo 1: Baixar a imagem

    -Abra o terminal e execute o seguinte comando para baixar a imagem: 🖥️

    -docker pull melquidocker/o-mini-mundo

    -Este comando irá buscar a imagem mais recente do repositório o-mini-mundo no Docker Hub e armazená-la localmente.

## ▶️ Passo 2: Executar a imagem

    -Após o download, execute a imagem em um contêiner com o seguinte comando:🖥️

    -🖥️ Terminal 1 - BackEnd
    -1-Docker instalado e em funcionamento em sua máquina.
    -2-docker compose up -d (subir imagem)
    -3-docker ps (Para validar se a imagem foi montada)    
    -4-docker compose exec app php artisan migrate (Certifique de que criou o banco de dados pelo SGBD) 

    -🖥️ Terminal 2 — FrontEnd 
    -2-npm run dev (VITE)

    -O '-d' executa o contêiner em segundo plano (modo "detached").

    -Agora, você pode acessar a aplicação através do navegador, indo para http://localhost/.

## 🔍 Passo 3: Verificar os contêineres em execução

    -Para confirmar que o contêiner está rodando corretamente, utilize o comando:🖥️

    -docker ps

    -Este comando lista todos os contêineres em execução, mostrando informações como ID, imagem utilizada, portas mapeadas e status.

## 🧹 Passo 4: Parar e remover todos contêiner

    -Quando não precisar mais da aplicação em execução, pare e remova o contêiner com os seguintes comandos:🖥️

    -docker compose down -v

---

## ✍️ Autor
    
    Melquisedeque Bispo de Jesus
    Este projeto é mantido como parte de um portfólio técnico e pode ser usado em processos seletivos ou provas práticas.

## 📄 Licença
  
    Este projeto é distribuído sob a Licença MIT
# Projeto Laravel com MySQL e Docker

Este projeto é um projeto de API construída com Laravel, utilizando MySQL como banco de dados e Docker para facilitar o desenvolvimento e a execução do ambiente.

## Pré-requisitos

- Docker e Docker Compose instalados na máquina.
- Git instalado para clonar o repositório.
- Composer instalado na máquina(V2x).

## Passos para Clonar e Configurar o Projeto

1. Clone o repositório:

    ```sh
    git clone <URL>
    cd myprojectlaravel
    ```

2. Crie um arquivo `.env` na raiz do projeto e adicione as seguintes configurações:

    ```env
    APP_NAME=Laravel
    APP_ENV=local
    APP_KEY=base64:1p7qVkorm6QM2KVc1iy+K6Zaz2/dyb9yCeKG5kOQor4=
    APP_DEBUG=true
    APP_URL=http://localhost

    LOG_CHANNEL=stack
    LOG_DEPRECATIONS_CHANNEL=null
    LOG_LEVEL=debug

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=laravel
    DB_USERNAME=laravel_user
    DB_PASSWORD=secret

    JWT_SECRET=o0gqNWvsQHv2PDabGQkjUs1bQ5j2QtwIu0zpeDcJIUmJjkurF8hFdWqUKra2JyEE
    ```

3. Suba o ambiente Docker na raiz da aplicação:

    ```sh
    docker-compose up -d
    ```

4. Instale as dependências do Laravel:

    ```sh
    composer install
    ```

5. Gere a chave secret da aplicação:

    ```sh
    php artisan jwt:secret
    ```

6. Comando para expor a chave secret da aplicação:

    ```sh
    php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
    ```

7. Execute as migrações para criar as tabelas no banco de dados:

    ```sh
    php artisan migrate
    ```

8. Execute o servidor:

    ```sh
    php artisan serve
    ```

## Estrutura do Projeto

- **app/Http/Controllers**: Contém os controladores da aplicação.
- **app/Models**: Contém os modelos Eloquent.
- **routes/api.php**: Define as rotas da API.
- **.env**: Arquivo de configuração de ambiente.

## Rotas da API

### Autenticação

- **Cadastro de Usuário (signup)**
  
    ```sh
    curl -X POST http://localhost:8000/api/signup \
    -H "Content-Type: application/json" \
    -d '{"email": "user@example.com", "password": "password"}'
    ```

- **Login de Usuário (login)**
  
    ```sh
    curl -X POST http://localhost:8000/api/login \
    -H "Content-Type: application/json" \
    -d '{"email": "user@example.com", "password": "password"}'
    ```

### Clientes

- **Listar Clientes**
  
    ```sh
    curl -X GET http://localhost:8000/api/clientes \
    -H "Authorization: Bearer SEU_TOKEN"
    ```

- **Adicionar Cliente**
  
    ```sh
    curl -X POST http://localhost:8000/api/clientes \
    -H "Content-Type: application/json" \
    -H "Authorization: Bearer SEU_TOKEN" \
    -d '{
        "nome": "Cliente Nome",
        "cpf": "320.583.140-34",
        "telefones": [
            {"numero_telefone": "(11) 98765-4321"},
            {"numero_telefone": "(11) 12345-6789"}
        ],
        "enderecos": [
            {"rua": "Rua Principal", "cidade": "São Paulo", "estado": "SP", "cep": "12345-678"},
            {"rua": "Avenida Secundária", "cidade": "Rio de Janeiro", "estado": "RJ", "cep": "54321-098"}
        ]
        }'
    ```

- **Mostrar Cliente**
  
    ```sh
    curl -X GET http://localhost:8000/api/clientes/1 \
    -H "Authorization: Bearer SEU_TOKEN"
    ```

- **Trazer Cliente com Vendas Filtradas por Mês e Ano**
  
    ```sh
    curl -X GET "http://localhost:8000/api/clientes/1?mes=6&ano=2024" \
    -H "Content-Type: application/json" \
    -H "Authorization: Bearer SEU_TOKEN"

    ```

- **Atualizar Cliente**
  
    ```sh
    curl -X PUT http://localhost:8000/api/clientes/1 \
    -H "Content-Type: application/json" \
    -H "Authorization: Bearer SEU_TOKEN" \
    -d '{
        "nome": "Cliente Novo",
        "cpf": "320.583.140-34",
        "telefones": [
            {"numero_telefone": "(11) 98765-4321"},
            {"numero_telefone": "(11) 12345-6789"}
        ],
        "enderecos": [
            {"rua": "Rua Principal", "cidade": "São Paulo", "estado": "SP", "cep": "12345-678"},
            {"rua": "Avenida Secundária", "cidade": "Rio de Janeiro", "estado": "RJ", "cep": "54321-098"}
        ]
        }'
    ```

- **Excluir Cliente**
  
    ```sh
    curl -X DELETE http://localhost:8000/api/clientes/1 \
    -H "Authorization: Bearer SEU_TOKEN"
    ```

### Produtos

- **Listar Produtos**
  
    ```sh
    curl -X GET http://localhost:8000/api/produtos \
    -H "Authorization: Bearer SEU_TOKEN"
    ```

- **Adicionar Produto**
  
    ```sh
    curl -X POST http://localhost:8000/api/produtos \
    -H "Content-Type: application/json" \
    -H "Authorization: Bearer SEU_TOKEN" \
    -d '{"nome": "Produto Nome", "preco": 100.00}'
    ```

- **Mostrar Produto**
  
    ```sh
    curl -X GET http://localhost:8000/api/produtos/1 \
    -H "Authorization: Bearer SEU_TOKEN"
    ```

- **Atualizar Produto**
  
    ```sh
    curl -X PUT http://localhost:8000/api/produtos/1 \
    -H "Content-Type: application/json" \
    -H "Authorization: Bearer SEU_TOKEN" \
    -d '{"nome": "Produto Nome", "preco": 100.00}'
    ```

- **Excluir Produto**
  
    ```sh
    curl -X DELETE http://localhost:8000/api/produtos/1 \
    -H "Authorization: Bearer SEU_TOKEN"
    ```

### Vendas

- **Registrar Venda**
  
    ```sh
    curl -X POST http://localhost:8000/api/vendas \
    -H "Content-Type: application/json" \
    -H "Authorization: Bearer SEU_TOKEN" \
    -d '{"cliente_id": 2, "produto_id": 1, "quantidade": 2, "preco_unitario": 100.00}'
    ```

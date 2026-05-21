# B2B Shop

Mini loja B2B em Laravel 13 com backoffice completo, front office, API REST com autenticação Sanctum, queue jobs com Redis, e broadcasting em tempo real com Socket.IO.

---

## Requisitos

| Dependência | Versão mínima |
|---|---|
| PHP | 8.3 |
| Composer | 2.x |
| Node.js | 18+ |
| MySQL | 8.0 |
| Redis | 7.x |

> Com Docker: apenas Docker + Docker Compose são necessários para MySQL e Redis.

---

## Instalação

### 1. Clonar o repositório

```bash
git clone <url-do-repo> b2b-shop
cd b2b-shop
```

### 2. Instalar dependências PHP e Node

```bash
composer install
npm install
```

### 3. Configurar o ambiente

```bash
cp .env.example .env
php artisan key:generate
```

Editar `.env` com as credenciais locais (ver secção [Configuração .env](#configuração-env)).

### 4. Iniciar MySQL e Redis com Docker

```bash
docker-compose up -d
```

Aguardar o MySQL ficar disponível (verificar com `docker-compose ps`).

### 5. Executar migrações e popular a base de dados

```bash
php artisan migrate --seed
```

### 6. Criar link de armazenamento público

```bash
php artisan storage:link
```

### 7. Compilar assets

```bash
npm run build
```

---

## Configuração .env

```dotenv
APP_NAME="B2B Shop"
APP_ENV=local
APP_KEY=          # gerado com php artisan key:generate
APP_DEBUG=true
APP_URL=http://localhost:8000
APP_LOCALE=pt

# Base de dados (MySQL via Docker na porta 3307)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3307
DB_DATABASE=b2b_shop
DB_USERNAME=b2b
DB_PASSWORD=secret

# Redis
REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379

# Queue — processar jobs de forma assíncrona
QUEUE_CONNECTION=redis

# Broadcasting — Socket.IO em tempo real
BROADCAST_CONNECTION=redis

# Cache
CACHE_STORE=redis

# Mail — log em desenvolvimento (ver storage/logs/laravel.log)
MAIL_MAILER=log

# Sessão
SESSION_DRIVER=database
```

---

## Execução

### Modo de desenvolvimento (todos os processos)

```bash
composer dev
```

Este comando inicia em paralelo: servidor Laravel, queue worker, log watcher e Vite dev server.

### Processos individuais

```bash
# Servidor Laravel
php artisan serve

# Queue worker (Redis) com filas prioritárias
php artisan queue:work redis --queue=high,default,low --tries=3 --timeout=60

# Socket.IO server (broadcasting em tempo real)
npx laravel-echo-server start

# Vite dev server (hot reload de assets)
npm run dev
```

---

## Credenciais de teste

Após `php artisan migrate --seed`:

| Papel | Email | Password |
|---|---|---|
| Administrador | admin@loja.com | password |
| Cliente | cliente@loja.com | password |

---

## Testes

```bash
# Todos os testes
composer test
# ou: php artisan test

# Apenas testes de regressão
php artisan test --filter=Regression

# Com cobertura (requer Xdebug)
php artisan test --coverage
```

Os testes usam SQLite in-memory — sem dependência de MySQL.

---

## Qualidade de código

```bash
# Formatar código (Pint)
composer lint

# Verificar formatação sem alterar
composer lint:check

# Análise estática nível 5 (PHPStan/Larastan)
composer analyse

# Pipeline completo (lint + analyse + test)
composer qa
```

---

## API REST

Base URL: `http://localhost:8000/api/v1`

Autenticação: Bearer token (Laravel Sanctum).

### Autenticação

```bash
# Login
curl -X POST http://localhost:8000/api/v1/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@loja.com","password":"password"}'

# Logout
curl -X POST http://localhost:8000/api/v1/logout \
  -H "Authorization: Bearer {token}"
```

### Produtos

```bash
# Listar produtos
curl http://localhost:8000/api/v1/products \
  -H "Authorization: Bearer {token}"

# Detalhe de produto
curl http://localhost:8000/api/v1/products/1 \
  -H "Authorization: Bearer {token}"

# Criar produto
curl -X POST http://localhost:8000/api/v1/products \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{"name":"Produto X","sku":"SKU-001","price":19.99,"stock":100,"category_id":1}'

# Actualizar produto
curl -X PUT http://localhost:8000/api/v1/products/1 \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{"price":24.99}'

# Eliminar produto
curl -X DELETE http://localhost:8000/api/v1/products/1 \
  -H "Authorization: Bearer {token}"
```

### Catálogos, Categorias, Clientes, Moradas

Seguem o mesmo padrão RESTful (`index`, `show`, `store`, `update`, `destroy`) sob:

- `GET/POST /api/v1/catalogs`
- `GET/PUT/DELETE /api/v1/catalogs/{id}`
- `GET/POST /api/v1/categories`
- `GET/PUT/DELETE /api/v1/categories/{id}`
- `GET/POST /api/v1/customers`
- `GET/PUT/DELETE /api/v1/customers/{id}`
- `GET/POST /api/v1/customers/{customer}/addresses`
- `GET/PUT/DELETE /api/v1/customers/{customer}/addresses/{address}`

### Encomendas (cliente autenticado)

```bash
# Listar as minhas encomendas
curl http://localhost:8000/api/v1/orders \
  -H "Authorization: Bearer {token}"

# Detalhe de encomenda
curl http://localhost:8000/api/v1/orders/1 \
  -H "Authorization: Bearer {token}"

# Criar encomenda
curl -X POST http://localhost:8000/api/v1/orders \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "address_id": 1,
    "items": [
      {"product_id": 1, "quantity": 2},
      {"product_id": 3, "quantity": 1}
    ]
  }'
```

### Encomendas (admin)

```bash
# Listar todas as encomendas
curl "http://localhost:8000/api/v1/admin/orders?status=pending" \
  -H "Authorization: Bearer {token}"

# Alterar estado de encomenda
curl -X PATCH http://localhost:8000/api/v1/admin/orders/1/status \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{"status":"confirmed"}'
```

### Respostas de erro

| Código | Situação |
|---|---|
| `401 Unauthorized` | Sem token ou token inválido |
| `403 Forbidden` | Sem permissão (ex: cliente a aceder endpoint de admin) |
| `404 Not Found` | Recurso não encontrado |
| `422 Unprocessable Entity` | Dados inválidos ou transição de estado inválida |

---

## Arquitectura

```
app/
├── Contracts/
│   ├── Repositories/   # Interfaces de repositório (DIP)
│   └── Services/       # Interfaces de serviço (DIP)
├── DTOs/               # Data Transfer Objects entre camadas
├── Enums/              # OrderStatus com transições válidas
├── Events/             # Eventos de domínio (OrderPlaced, OrderStatusChanged)
├── Exceptions/         # Exceções de domínio com semântica própria
├── Http/
│   ├── Controllers/
│   │   ├── Admin/      # Backoffice
│   │   ├── Api/V1/     # REST API versionada
│   │   │   └── Admin/  # Endpoints de admin na API
│   │   └── Shop/       # Front office
│   ├── Requests/       # Form Requests (validação)
│   │   ├── Admin/
│   │   ├── Api/V1/
│   │   └── Shop/
│   └── Resources/      # API Resources (serialização JSON)
│       └── V1/
├── Jobs/               # Queue jobs assíncronos
├── Listeners/          # Handlers de eventos
├── Models/             # Eloquent com relações, casts e scopes
├── Policies/           # Autorização por entidade
├── Repositories/       # Implementações Eloquent dos repositórios
└── Services/           # Lógica de negócio
```

### Princípios aplicados

- **Controllers magros** — delegam para Services via DTOs; sem lógica de negócio
- **Repository pattern** — abstracção sobre Eloquent, testável via interfaces
- **DTOs** — tipagem forte entre camadas (sem arrays anónimos)
- **Custom Exceptions** — `InsufficientStockException`, `InvalidOrderStateTransitionException`, `OrderNotOwnedByCustomerException`
- **OrderStatus Enum** — define transições válidas com `canTransitionTo()`
- **Form Requests** — validação declarativa em todos os formulários e endpoints API
- **API Resources** — serialização JSON desacoplada dos modelos

### Decisões técnicas

| Decisão | Justificação |
|---|---|
| `unit_price` snapshot em `order_items` | Preço no momento da compra — não pode mudar com alterações futuras ao produto |
| SQLite nos testes | Isolamento e velocidade — sem dependência de MySQL em CI |
| PHPUnit em vez de Pest | Incompatibilidade do Pest com `laravel/pao` no Laravel 13 |
| Redis para queue e broadcasting | Suporte a filas prioritárias (`high,default,low`) e pub/sub para Socket.IO |
| Soft Deletes em `products` e `customers` | Preservar integridade referencial sem perder histórico de encomendas |
| API versionada `/api/v1/` | Zero custo, boa prática, evita breaking changes futuros |
| laravel-echo-server em vez de Reverb | Reverb usa protocolo Pusher; laravel-echo-server expõe Socket.IO nativo conforme requisito |

---

## Funcionalidades

### Backoffice (`/admin`)

- **Catálogos** — CRUD completo, toggle activo/inactivo, associação de produtos
- **Categorias** — CRUD com hierarquia pai/filho, dropdown com exclusão de ciclos
- **Produtos** — CRUD com upload de imagem, filtro por categoria/estado, paginação
- **Clientes** — CRUD com toggle de bloqueio, listagem de moradas
- **Moradas** — CRUD nested por cliente
- **Encomendas** — listagem com filtros, detalhe completo, alteração de estado com validação de transições

### Front Office (`/shop`)

- **Catálogo** — listagem de produtos activos com filtro por categoria e pesquisa, paginação
- **Carrinho** — sessão, adicionar/alterar/remover, validação de stock
- **Checkout** — selecção de morada, criação de encomenda em transacção, decremento de stock
- **Encomendas** — histórico do cliente, detalhe com actualização em tempo real via Socket.IO

### API REST (`/api/v1`)

- 25 endpoints com autenticação Sanctum
- CRUD completo: produtos, catálogos, categorias, clientes, moradas
- Encomendas para cliente autenticado (`index`, `show`, `store`)
- Gestão de encomendas para admin (`index`, `show`, `updateStatus`)
- Respostas estruturadas com API Resources e paginação Laravel

### Tempo real

O estado das encomendas actualiza automaticamente na página do cliente (sem reload) quando o administrador altera o estado no backoffice, via canal privado Socket.IO `orders.{id}`.

### Queues (Redis)

| Job | Trigger | Fila |
|---|---|---|
| `SendOrderConfirmationEmail` | `OrderPlaced` | `default` |
| `UpdateProductStock` | `OrderPlaced` | `default` |
| `NotifyOrderStatusChanged` | `OrderStatusChanged` | `high` |
| `AuditLogJob` | `OrderStatusChanged` | `low` |

---

## Dados de seed

O seeder cria:

- 1 administrador (`admin@loja.com`)
- 5 clientes (incluindo `cliente@loja.com`)
- 2 catálogos activos
- 4 categorias (2 com subcategorias)
- 20 produtos activos + 5 inactivos
- Encomendas em vários estados (`pending`, `confirmed`, `shipped`, `completed`)

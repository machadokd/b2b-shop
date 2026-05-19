# Roadmap — Mini Loja B2B em Laravel 13

> **Prazo:** 2 dias | **Estratégia:** máximo impacto no mínimo tempo

---

## Princípio de Priorização (2 dias)

Com 2 dias, o objectivo é ter **tudo funcional e bem estruturado**, não perfeito. A ordem abaixo respeita as dependências técnicas e maximiza o que o avaliador verá primeiro.

**Dia 1** → Fundações + BD + Auth + Backoffice completo  
**Dia 2** → Front office + API + Queues + Socket.IO + Testes essenciais + README

---

## DIA 1

### Bloco 1 — Setup (1-2h) ✅

- [x] `composer create-project laravel/laravel b2b-shop`
- [x] Git init + `.gitignore` + commit inicial: `chore: initial Laravel 13 setup`
- [x] Configurar `.env` (MySQL 3307, Redis, Queue, Broadcast, Mail)
- [x] Instalar dependências:
  ```bash
  composer require laravel/sanctum
  npm install bootstrap @popperjs/core laravel-echo socket.io-client
  npm install --save-dev laravel-echo-server
  ```
- [x] Configurar `vite.config.js` com Bootstrap
- [x] Criar estrutura de pastas:
  ```
  app/Contracts/Services/
  app/Contracts/Repositories/
  app/Services/
  app/Repositories/
  app/DTOs/
  app/Exceptions/
  app/Enums/
  ```
- [x] Configurar Laravel Pint (`pint.json` com preset `laravel`)
- [x] Configurar Larastan (`phpstan.neon` nível 5)
- [x] Criar `OrderStatus` Enum com `label()`, `allowedTransitions()`, `canTransitionTo()`
- [x] `docker-compose.yml` com MySQL 8 (porta 3307) + Redis 7
- [x] `laravel-echo-server.json` pré-configurado

### Bloco 2 — Base de Dados (1.5h) ✅

**Migrations — nesta ordem exacta (respeita FK constraints):**

- [x] `users` — adicionar `role ENUM('admin','customer')`, `is_active BOOL DEFAULT true`
- [x] `customers` — `user_id FK`, `company_name`, `nif`, `phone`, `is_blocked`, `soft_deletes`
- [x] `addresses` — `customer_id FK`, `recipient_name`, `address_line`, `postal_code`, `city`, `country`, `nif nullable`, `is_default BOOL`
- [x] `catalogs` — `name`, `slug UNIQUE`, `description`, `is_active BOOL`
- [x] `categories` — `name`, `slug UNIQUE`, `parent_id FK NULLABLE` (self-referencing)
- [x] `products` — todos os campos + `soft_deletes`; índice em `(is_active, category_id)` e `sku`
- [x] `catalog_product` — pivot M:N; PK composta
- [x] `orders` — `customer_id FK`, `address_id FK`, `status ENUM`, `total DECIMAL(10,2)`, `notes nullable`; índice em `(customer_id, status)`
- [x] `order_items` — `order_id FK`, `product_id FK`, `quantity`, `unit_price DECIMAL(10,2)`
- [x] `php artisan migrate` — verificar sem erros

**Modelos Eloquent:**

- [x] `User` — `$fillable`, relação `customer()`, helper `isAdmin(): bool`
- [x] `Customer` — `hasMany(Address)`, `hasMany(Order)`, `belongsTo(User)`, `SoftDeletes`, scope `active()`
- [x] `Address` — `belongsTo(Customer)`, scope `default()`
- [x] `Catalog` — `belongsToMany(Product)`, scope `active()`
- [x] `Category` — `hasMany(Category, 'parent_id')` (filhas), `belongsTo(Category, 'parent_id')` (pai), `hasMany(Product)`
- [x] `Product` — `belongsToMany(Catalog)`, `belongsTo(Category)`, `SoftDeletes`, scope `active()`, cast `price` para `float`
- [x] `Order` — `hasMany(OrderItem)`, `belongsTo(Customer)`, `belongsTo(Address)`, cast `status` → `OrderStatus::class`
- [x] `OrderItem` — `belongsTo(Order)`, `belongsTo(Product)`

**Factories & Seeders:**

- [x] `UserFactory` — states `admin()` e `customer()`
- [x] `CustomerFactory` com `withUser()` state
- [x] `AddressFactory`
- [x] `CatalogFactory`
- [x] `CategoryFactory` com `withParent()` state
- [x] `ProductFactory` — states `active()`, `inactive()`, `lowStock()`
- [x] `OrderFactory` com `OrderItemFactory`
- [x] `DatabaseSeeder`:
  ```php
  // 1 admin + 5 clientes + 2 catálogos + 4 categorias (2 com subcategorias)
  // 20 produtos activos + 5 inactivos + ordens em vários estados
  Admin:    admin@loja.com / password
  Cliente:  cliente@loja.com / password
  ```

### Bloco 3 — Autenticação & Autorização (1h) ✅

- [x] Criar `EnsureRole` middleware + `EnsureCustomerActive` middleware
- [x] Registar aliases em `bootstrap/app.php`
- [x] Criar `LoginController` para admin (redireciona `/admin/dashboard`)
- [x] Criar `LoginController` para cliente (redireciona `/shop/products`)
- [x] Views de login: `auth/admin-login.blade.php` e `auth/login.blade.php`
- [x] Criar Policies: `OrderPolicy`, `ProductPolicy`
- [x] Registar policies via `#[Policy]` attribute ou `AuthServiceProvider`
- [x] Definir rotas em `routes/web.php`:
  ```php
  Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(...);
  Route::prefix('shop')->middleware(['auth', 'role:customer'])->name('shop.')->group(...);
  ```
- [ ] Testar: admin → `/admin/dashboard`, cliente → `/shop/products`, acesso cruzado → 403

### Bloco 4 — Interfaces, DTOs e Exceptions (45min) ✅

- [x] Definir `BaseRepositoryInterface` com `findAll`, `findById`, `create`, `update`, `delete`
- [x] Definir interfaces específicas: `OrderRepositoryInterface`, `ProductRepositoryInterface`, `CustomerRepositoryInterface`
- [x] Definir interfaces de serviço: `OrderServiceInterface`, `ProductServiceInterface`
- [x] Implementar repositórios Eloquent concretos em `app/Repositories/`
- [x] Criar DTOs: `StoreOrderDTO`, `StoreOrderItemDTO`, `StoreProductDTO`, `UpdateOrderStatusDTO`
- [x] Criar Custom Exceptions:
  - `InsufficientStockException`
  - `InvalidOrderStateTransitionException`
  - `OrderNotOwnedByCustomerException`
- [x] Registar handlers em `bootstrap/app.php`
- [x] Bindings em `AppServiceProvider`

### Bloco 5 — Backoffice (3h) ✅

> Padrão: Controller → FormRequest → DTO → Service → Repository → View

**Layout:**

- [x] `layouts/admin.blade.php` com sidebar Bootstrap, navbar, flash messages (`session('success')`, `session('error')`)
- [x] Componente Blade `@component('components.confirm-modal')` para ações destrutivas

**Catálogos** (`Admin\CatalogController`):

- [x] CRUD completo (index, create, store, edit, update, destroy)
- [x] Ação toggle `is_active`
- [x] Associar/remover produtos (multi-select ou checkbox list)
- [x] `StoreCatalogRequest` + `UpdateCatalogRequest`

**Categorias** (`Admin\CategoryController`):

- [x] CRUD completo
- [x] Dropdown de categoria pai no form (excluir a própria categoria para evitar ciclos)
- [x] Listagem com indicação visual de hierarquia

**Produtos** (`Admin\ProductController`):

- [x] CRUD completo
- [x] Upload de imagem com `Storage::disk('public')->put()`
- [x] `StoreProductRequest` — validar SKU único, preço positivo, stock >= 0
- [x] Listagem com filtro por categoria e estado (query scopes)
- [x] Paginação (15/página)
- [x] Toggle `is_active`

**Clientes** (`Admin\CustomerController`):

- [x] CRUD completo
- [x] Toggle `is_blocked`
- [x] Ver moradas do cliente no detalhe

**Moradas** (`Admin\AddressController`):

- [x] CRUD nested em cliente (`/admin/customers/{customer}/addresses`)
- [x] Todos os campos obrigatórios presentes

**Encomendas** (`Admin\OrderController`):

- [x] `index` com filtros por estado e cliente, paginação
- [x] `show` com items, morada, estado actual, histórico
- [x] `updateStatus` — valida transição via `OrderStatus::canTransitionTo()`; lança `InvalidOrderStateTransitionException` se inválida
- [ ] Ao guardar → `OrderStatusChanged::dispatch($order)` → broadcasting + queue jobs (Bloco 8/9)

---

## DIA 2

### Bloco 6 — Front Office (2.5h) ✅

**Layout:**

- [x] `layouts/shop.blade.php` — navbar responsive com badge do carrinho, footer
- [x] Responsive mobile-first com Bootstrap 5

**Catálogo** (`Shop\ProductController`):

- [x] `index` — listagem com eager loading `with('category')`, paginação, filtro categoria, pesquisa (nome/SKU), só produtos activos
- [x] `show` — detalhe com imagem, preço, stock, botão "Adicionar ao Carrinho" (desactivado se stock=0)

**Carrinho** (`Shop\CartController`):

- [x] Carrinho em sessão (`session('cart')`)
- [x] `add` — valida stock antes de adicionar; lança `InsufficientStockException`
- [x] `update` — altera quantidade
- [x] `remove` — remove item
- [x] View com tabela, total, link para checkout

**Checkout** (`Shop\CheckoutController`):

- [x] `show` — form com dropdown de moradas do cliente
- [x] `store` via `DB::transaction()`:
  1. `validateStock()` — guard clause antes de abrir transação
  2. Criar `Order` (status `Pending`)
  3. Criar `OrderItems` com snapshot de `unit_price`
  4. Stock decrementado (sync; Bloco 8 substitui por `UpdateProductStock` job)
  5. Limpar carrinho da sessão
  6. Redirecionar para `shop.orders.show`
- [x] `StoreOrderRequest` — valida carrinho não vazio, `address_id` pertence ao cliente autenticado

**Encomendas do cliente** (`Shop\OrderController`):

- [x] `index` — só encomendas do cliente autenticado; paginação; badges de estado coloridos
- [x] `show` — detalhe com items, morada, estado; listener Socket.IO preparado (Bloco 9)

**Socket.IO no front office:**

- [ ] Integração Echo activada (Bloco 9)

### Bloco 7 — API REST (1.5h) ✅

- [x] Rotas em `routes/api.php` com prefix `/v1`
- [x] `Api\V1\AuthController` — POST `/api/v1/login`, POST `/api/v1/logout`
- [x] 5 API Resources: `CatalogResource`, `CategoryResource`, `ProductResource`, `CustomerResource`, `AddressResource`
- [x] 5 Controllers API com todos os endpoints CRUD
- [x] Form Requests API dedicados (`app/Http/Requests/Api/V1/`)
- [x] Handler de exceções JSON em `bootstrap/app.php`:
  - `ModelNotFoundException` → 404
  - `ValidationException` → 422
  - `AuthenticationException` → 401
- [ ] Testar com cURL:
  ```bash
  # Login
  curl -X POST http://localhost:8000/api/v1/login \
    -H "Content-Type: application/json" \
    -d '{"email":"admin@loja.com","password":"password"}'

  # Listar produtos
  curl http://localhost:8000/api/v1/products \
    -H "Authorization: Bearer {token}"
  ```

### Bloco 8 — Queues & Jobs (1h) ✅

- [x] `QUEUE_CONNECTION=redis` no `.env`
- [x] `php artisan queue:failed-table && php artisan migrate`
- [x] Criar Jobs:
  - [x] `SendOrderConfirmationEmail` — Mailable simulado (log driver em dev)
  - [x] `NotifyOrderStatusChanged` — email simulado ao cliente
  - [x] `UpdateProductStock` — decrementa stock via `ProductRepository`
  - [x] `AuditLogJob` — `Log::channel('audit')->info(...)`
- [x] Criar Events: `OrderPlaced`, `OrderStatusChanged`
- [x] Criar Listeners e associar aos Events
- [x] Registar via `Event::listen` em `AppServiceProvider`
- [ ] Testar: `php artisan queue:work` + criar encomenda → verificar jobs processados

### Bloco 9 — Socket.IO com laravel-echo-server (1h)

- [ ] `BROADCAST_DRIVER=redis` no `.env`
- [ ] Publicar config broadcasting: `php artisan install:broadcasting` (ou configurar manualmente)
- [ ] Gerar `laravel-echo-server.json`:
  ```bash
  npx laravel-echo-server init
  # authHost: http://localhost:8000
  # database: redis
  # port: 6001
  ```
- [ ] Definir canal privado em `routes/channels.php`
- [ ] `OrderStatusChanged` implementa `ShouldBroadcast`
- [ ] Configurar `resources/js/echo.js` com broadcaster `socket.io`
- [ ] `npm run build`
- [ ] Teste end-to-end:
  1. Abrir página de detalhe da encomenda como cliente
  2. Em outro tab, alterar estado como admin
  3. Verificar atualização automática no front office

### Bloco 10 — Testes (1.5h)

> Foco: testes de regressão e alguns unitários. Os funcionais levam mais tempo mas são os mais visíveis.

**Configurar ambiente de testes** (SQLite in-memory, queue sync, broadcast log):

- [ ] `phpunit.xml` com envs de teste

**Unitários (rápidos, alto valor):**

- [ ] `OrderStatusTransitionTest` — `canTransitionTo()` válido e inválido
- [ ] `StockValidationTest` — `InsufficientStockException` lançada quando stock insuficiente
- [ ] `OrderTotalCalculationTest` — total calculado correctamente

**Funcionais:**

- [ ] `AdminLoginTest` — login OK → dashboard; login falhado → erro
- [ ] `CustomerLoginTest` — login OK → shop; cliente bloqueado → erro
- [ ] `CheckoutTest` — encomenda criada + stock decrementado + sessão limpa
- [ ] `AuthenticatedApiTest` — 401 sem token, 200 com token válido

**Regressão (os mais importantes para o avaliador):**

- [ ] `CustomerOrderIsolationTest` — cliente A não acede a encomenda de cliente B → 403
- [ ] `BackofficeAccessControlTest` — cliente não acede `/admin/*` → redirect/403
- [ ] `InactiveProductVisibilityTest` — produto inativo não aparece na listagem front office
- [ ] `EmptyOrderRejectionTest` — checkout com carrinho vazio → 422
- [ ] `UnauthenticatedApiTest` — todos os endpoints API sem token → 401

```bash
php artisan test                          # todos
php artisan test --filter=Regression     # só regressão
php artisan test --coverage              # com cobertura (se Xdebug instalado)
```

### Bloco 11 — README & Polimento Final (1h)

**README.md:**

- [ ] Requisitos técnicos (PHP 8.3, Node 18+, Redis, MySQL)
- [ ] Passos de instalação (clone → composer → npm → .env → migrate → seed → storage:link)
- [ ] Configuração `.env` com exemplo comentado
- [ ] Como correr: `php artisan serve`
- [ ] Como correr queue worker: `php artisan queue:work redis --queue=high,default,low`
- [ ] Como correr laravel-echo-server: `npx laravel-echo-server start`
- [ ] Como correr testes: `php artisan test`
- [ ] Credenciais de teste (admin + cliente)
- [ ] Endpoints API com exemplos cURL
- [ ] Descrição da arquitectura (camadas, SOLID, DTOs, Exceptions)
- [ ] Decisões técnicas justificadas
- [ ] Funcionalidades implementadas

**Polimento:**

- [ ] `./vendor/bin/pint` — fix automático de code style
- [ ] `./vendor/bin/phpstan analyse` — verificar sem erros críticos
- [ ] Verificar responsive (DevTools mobile)
- [ ] Confirmar todos os flash messages de sucesso/erro
- [ ] Confirmar confirmações antes de eliminações
- [ ] Edge cases:
  - Produto sem stock → botão desactivado no front office
  - Cliente bloqueado → não consegue fazer login
  - Transição de estado inválida → mensagem de erro clara
- [ ] Commit final: `feat: complete B2B shop implementation`
- [ ] Tag: `git tag v1.0.0`

---

## Checklist de Entrega

### Arquitectura & Qualidade
- [ ] Controllers magros — zero lógica de negócio
- [ ] Services com interfaces (DIP)
- [ ] Repositories com interfaces (LSP)
- [ ] DTOs entre camadas (tipagem forte)
- [ ] Custom Exceptions com semântica de domínio
- [ ] Form Requests em todos os formulários e endpoints API
- [ ] API Resources para toda a serialização JSON
- [ ] Eager loading em todas as listagens (zero N+1)
- [ ] `DB::transaction()` no checkout
- [ ] `OrderStatus` Enum com transições válidas
- [ ] Pint a passar sem erros
- [ ] Larastan nível 5 a passar

### Funcionalidades
- [ ] Backoffice CRUD: catálogos, categorias, produtos, clientes, moradas, encomendas
- [ ] Front office: listagem, filtro, pesquisa, carrinho, checkout, histórico
- [ ] API REST `/api/v1/` com Sanctum (25 endpoints)
- [ ] Queue jobs com Redis (4 jobs funcionais)
- [ ] Socket.IO em tempo real (estado de encomenda)
- [ ] Testes: unitários + funcionais + regressão

### Documentação
- [ ] README.md completo e reproduzível
- [ ] `.env.example` com todas as variáveis
- [ ] Seeders com dados realistas
- [ ] Credenciais de exemplo documentadas

---

## Extras se Sobrar Tempo

| Extra | Esforço | Impacto |
|---|---|---|
| Rate limiting na API (`throttle:60,1`) | 5min | Médio |
| Soft Deletes em produtos/clientes | 10min | Alto |
| Índices BD explícitos | 10min | Alto |
| Dashboard admin (contadores) | 30min | Médio |
| Coleção Postman exportável | 20min | Médio |
| `docker-compose.yml` (MySQL + Redis) | 30min | Alto |
| Audit log table (ao invés de ficheiro) | 20min | Baixo |

---

## Comandos de Referência Rápida

```bash
# Setup
composer install && npm install
cp .env.example .env && php artisan key:generate
php artisan migrate --seed
php artisan storage:link

# Dev
php artisan serve                                              # Laravel (porta 8000)
php artisan queue:work redis --queue=high,default,low         # Queue worker
npx laravel-echo-server start                                  # Socket.IO server (porta 6001)
npm run dev                                                    # Vite dev server

# Qualidade
./vendor/bin/pint                  # Code style
./vendor/bin/phpstan analyse       # Análise estática
php artisan test                   # Testes
php artisan test --filter=Regression

# Git
git add -A && git commit -m "feat: ..."
git tag v1.0.0
```

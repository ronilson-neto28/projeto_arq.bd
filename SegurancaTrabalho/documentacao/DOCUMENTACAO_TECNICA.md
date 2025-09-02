# Documentação Técnica - Sistema de Segurança do Trabalho

## Visão Geral do Sistema

O Sistema de Segurança do Trabalho é uma aplicação web desenvolvida em Laravel 10 que gerencia exames ocupacionais, funcionários, empresas e encaminhamentos médicos. O sistema utiliza arquitetura MVC (Model-View-Controller) e segue as melhores práticas do framework Laravel.

## Arquitetura do Sistema

### Tecnologias Utilizadas

- **Backend**: Laravel 10 (PHP 8.1+)
- **Frontend**: Blade Templates, Bootstrap 5, JavaScript
- **Banco de Dados**: PostgreSQL 15 (Docker)
- **Containerização**: Docker e Docker Compose
- **Autenticação**: Laravel Breeze
- **Email**: Laravel Mail com Mailpit para desenvolvimento
- **Gerenciamento de Assets**: Vite

### Estrutura MVC

```
app/
├── Http/
│   ├── Controllers/          # Controladores da aplicação
│   ├── Middleware/           # Middlewares personalizados
│   └── Requests/             # Form Requests para validação
├── Models/                   # Modelos Eloquent
├── Mail/                     # Classes de email
└── Providers/                # Service Providers
```

## Modelos (Models)

### 1. User (Usuário)

**Arquivo**: `app/Models/User.php`

**Responsabilidade**: Gerencia autenticação e dados dos usuários do sistema.

**Campos Principais**:
- `name`: Nome do usuário
- `email`: Email (único)
- `password`: Senha criptografada
- `email_verified_at`: Data de verificação do email

**Relacionamentos**:
- Nenhum relacionamento direto com outros modelos

**Funcionalidades**:
- Autenticação via Laravel Breeze
- Reset de senha via email
- Verificação de email

### 2. Encaminhamento

**Arquivo**: `app/Models/Encaminhamento.php`

**Responsabilidade**: Representa os encaminhamentos médicos para exames ocupacionais.

**Campos Principais**:
- `numero_guia`: Número único da guia
- `data_emissao`: Data de emissão
- `funcionario_id`: ID do funcionário
- `empresa_id`: ID da empresa
- `cargo_id`: ID do cargo
- `tipo_exame`: Tipo do exame (admissional, periódico, etc.)
- `riscos_extra_json`: Riscos adicionais em formato JSON

**Relacionamentos**:
- `belongsTo(Funcionario::class)`: Pertence a um funcionário
- `belongsTo(Empresa::class)`: Pertence a uma empresa
- `belongsTo(Cargo::class)`: Pertence a um cargo
- `hasMany(EncaminhamentoItem::class)`: Possui vários itens

**Casts**:
- `data_emissao`: date
- `data_vencimento`: date
- `riscos_extra_json`: array

### 3. Funcionario

**Arquivo**: `app/Models/Funcionario.php`

**Responsabilidade**: Gerencia dados dos funcionários das empresas.

**Campos Principais**:
- `nome`: Nome completo
- `cpf`: CPF do funcionário
- `data_nascimento`: Data de nascimento
- `empresa_id`: ID da empresa
- `cargo_id`: ID do cargo

**Relacionamentos**:
- `belongsTo(Empresa::class)`: Pertence a uma empresa
- `belongsTo(Cargo::class)`: Possui um cargo
- `hasMany(Encaminhamento::class)`: Possui vários encaminhamentos

### 4. Empresa

**Arquivo**: `app/Models/Empresa.php`

**Responsabilidade**: Gerencia dados das empresas clientes.

**Campos Principais**:
- `razao_social`: Razão social da empresa
- `cnpj`: CNPJ da empresa
- `endereco`: Endereço completo
- `telefone`: Telefone de contato

**Relacionamentos**:
- `hasMany(Funcionario::class)`: Possui vários funcionários
- `hasMany(Encaminhamento::class)`: Possui vários encaminhamentos

### 5. Cargo

**Arquivo**: `app/Models/Cargo.php`

**Responsabilidade**: Define os cargos e suas características.

**Campos Principais**:
- `nome`: Nome do cargo
- `descricao`: Descrição das atividades
- `riscos`: Riscos associados ao cargo

**Relacionamentos**:
- `hasMany(Funcionario::class)`: Possui vários funcionários
- `hasMany(Encaminhamento::class)`: Possui vários encaminhamentos

## Controladores (Controllers)

### 1. AuthController

**Arquivo**: `app/Http/Controllers/AuthController.php`

**Responsabilidade**: Gerencia autenticação, login, logout e registro.

**Métodos Principais**:
- `login()`: Exibe formulário de login
- `authenticate()`: Processa autenticação
- `logout()`: Realiza logout
- `register()`: Exibe formulário de registro
- `store()`: Processa registro de novo usuário

### 2. PasswordResetController

**Arquivo**: `app/Http/Controllers/PasswordResetController.php`

**Responsabilidade**: Gerencia reset de senhas via email.

**Métodos Principais**:
- `showLinkRequestForm()`: Exibe formulário para solicitar reset
- `sendResetLinkEmail()`: Envia email com link de reset
- `showResetForm()`: Exibe formulário de nova senha
- `reset()`: Processa reset da senha

**Fluxo de Reset de Senha**:
1. Usuário solicita reset informando email
2. Sistema gera token único e envia email
3. Usuário clica no link do email
4. Sistema valida token e permite nova senha
5. Senha é atualizada no banco de dados

### 3. ListarExamesController

**Arquivo**: `app/Http/Controllers/ListarExamesController.php`

**Responsabilidade**: Gerencia listagem e impressão de exames.

**Métodos Principais**:
- `index()`: Lista todos os exames
- `imprimir($id)`: Gera impressão de encaminhamento específico
- `formatCpf($cpf)`: Formata CPF no padrão brasileiro
- `formatCnpj($cnpj)`: Formata CNPJ no padrão brasileiro

**Funcionalidades Especiais**:
- Utiliza dados mock para demonstração
- Formatação automática de CPF/CNPJ
- Geração de PDFs para impressão
- Mapeamento dinâmico de dados por ID

### 4. GerarExameController

**Arquivo**: `app/Http/Controllers/GerarExameController.php`

**Responsabilidade**: Gerencia criação de novos exames.

**Métodos Principais**:
- `index()`: Exibe formulário de criação
- `store()`: Processa criação de novo exame
- `validate()`: Valida dados do formulário

## Sistema de Email

### Configuração

O sistema utiliza Laravel Mail para envio de emails, configurado no arquivo `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@segurancatrabalho.com"
MAIL_FROM_NAME="Sistema Segurança Trabalho"
```

### Mailpit (Desenvolvimento)

**Função**: Captura emails enviados durante desenvolvimento
**Acesso**: http://localhost:8025
**Vantagens**:
- Não envia emails reais
- Interface web para visualização
- Teste de templates de email

### Classes de Email

**Localização**: `app/Mail/`

**Emails Implementados**:
- `ResetPasswordMail`: Email de reset de senha
- `WelcomeMail`: Email de boas-vindas
- `ExamNotificationMail`: Notificação de exames

### Templates de Email

**Localização**: `resources/views/emails/`

**Estrutura**:
```
emails/
├── layout.blade.php          # Layout base
├── reset-password.blade.php   # Template de reset
└── welcome.blade.php          # Template de boas-vindas
```

## Infraestrutura Docker

### Docker Compose

O sistema utiliza Docker Compose para gerenciar os serviços necessários:

**Arquivo**: `docker-compose.yml`

**Serviços Configurados**:

#### 1. Aplicação PHP (app)
- **Imagem**: php:8.2-fpm
- **Container**: laravel-app
- **Porta**: 9000
- **Volume**: Código da aplicação montado em `/var/www/html`

#### 2. Banco de Dados (db)
- **Imagem**: postgres:15
- **Container**: tcc-postgres
- **Porta**: 5432
- **Credenciais**:
  - Database: postgres
  - Usuário: postgres
  - Senha: 1234
- **Volume**: pgdata para persistência dos dados

#### 3. Mailpit (mailpit)
- **Imagem**: axllent/mailpit
- **Container**: mailpit
- **Portas**:
  - 8025: Interface web
  - 1025: Servidor SMTP
- **Funcionalidades**:
  - Captura emails enviados pela aplicação
  - Interface web para visualização
  - Máximo de 5000 mensagens
  - Autenticação SMTP flexível

### Comandos Docker Úteis

```bash
# Iniciar todos os serviços
docker-compose up -d

# Parar todos os serviços
docker-compose down

# Ver logs dos serviços
docker-compose logs

# Ver status dos containers
docker-compose ps

# Acessar container do PostgreSQL
docker exec -it tcc-postgres psql -U postgres -d postgres

# Backup do banco
docker exec tcc-postgres pg_dump -U postgres postgres > backup.sql

# Restaurar backup
docker exec -i tcc-postgres psql -U postgres -d postgres < backup.sql
```

## Banco de Dados

### Estrutura das Tabelas (PostgreSQL)

#### users
```sql
CREATE TABLE users (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

#### empresas
```sql
CREATE TABLE empresas (
    id BIGSERIAL PRIMARY KEY,
    razao_social VARCHAR(255) NOT NULL,
    cnpj VARCHAR(18) UNIQUE NOT NULL,
    endereco TEXT,
    telefone VARCHAR(20),
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

#### cargos
```sql
CREATE TABLE cargos (
    id BIGSERIAL PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT,
    riscos TEXT,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

#### funcionarios
```sql
CREATE TABLE funcionarios (
    id BIGSERIAL PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    cpf VARCHAR(14) UNIQUE NOT NULL,
    data_nascimento DATE,
    empresa_id BIGINT REFERENCES empresas(id),
    cargo_id BIGINT REFERENCES cargos(id),
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

#### encaminhamentos
```sql
CREATE TABLE encaminhamentos (
    id BIGSERIAL PRIMARY KEY,
    numero_guia VARCHAR(50) UNIQUE NOT NULL,
    data_emissao DATE NOT NULL,
    data_vencimento DATE,
    funcionario_id BIGINT REFERENCES funcionarios(id),
    empresa_id BIGINT REFERENCES empresas(id),
    cargo_id BIGINT REFERENCES cargos(id),
    tipo_exame VARCHAR(50) CHECK (tipo_exame IN ('admissional', 'periodico', 'demissional', 'mudanca_funcao', 'retorno_trabalho')),
    riscos_extra_json JSONB,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

### Migrações

**Localização**: `database/migrations/`

**Ordem de Execução**:
1. `create_users_table`
2. `create_empresas_table`
3. `create_cargos_table`
4. `create_funcionarios_table`
5. `create_encaminhamentos_table`
6. `create_password_reset_tokens_table`

### Seeders

**Localização**: `database/seeders/`

**Dados Iniciais**:
- `UserSeeder`: Usuários padrão do sistema
- `EmpresaSeeder`: Empresas de exemplo
- `CargoSeeder`: Cargos padrão
- `FuncionarioSeeder`: Funcionários de teste

## Sistema de Rotas

**Arquivo**: `routes/web.php`

### Rotas de Autenticação
```php
// Login
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Registro
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'store']);
```

### Rotas de Reset de Senha
```php
// Solicitar reset
Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])
    ->name('password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])
    ->name('password.email');

// Processar reset
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])
    ->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'reset'])
    ->name('password.update');
```

### Rotas Protegidas (Middleware auth)
```php
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/forms/listar-exames', [ListarExamesController::class, 'index'])->name('forms.exames.listar');
    Route::get('/forms/gerar-exame', [GerarExameController::class, 'index'])->name('forms.exames.gerar');
    Route::get('/imprimir-exame/{id}', [ListarExamesController::class, 'imprimir'])->name('forms.exames.imprimir');
});
```

## Sistema de Views (Blade Templates)

### Estrutura de Layouts

**Layout Principal**: `resources/views/layouts/app.blade.php`

**Características**:
- Bootstrap 5 para estilização
- Menu de navegação responsivo
- Sistema de notificações
- Scripts JavaScript globais

### Views de Autenticação

**Localização**: `resources/views/auth/`

**Templates**:
- `login.blade.php`: Formulário de login
- `register.blade.php`: Formulário de registro
- `forgot-password.blade.php`: Solicitar reset de senha
- `reset-password.blade.php`: Formulário de nova senha

### Views de Formulários

**Localização**: `resources/views/forms/`

**Templates**:
- `listar-exames.blade.php`: Lista de exames com botão imprimir
- `gerar-exame.blade.php`: Formulário de criação de exames
- `imprimir-encaminhamento.blade.php`: Template de impressão

### Sistema de Impressão

**Template**: `imprimir-encaminhamento.blade.php`

**Características**:
- Layout otimizado para impressão
- CSS específico para mídia print
- Formatação automática de CPF/CNPJ
- Dados dinâmicos baseados no ID
- Abertura em nova aba

## Middleware e Segurança

### Middleware de Autenticação

**Função**: Protege rotas que requerem login
**Implementação**: Laravel Breeze padrão
**Redirecionamento**: Usuários não autenticados são redirecionados para `/login`

### Proteção CSRF

**Implementação**: Tokens CSRF em todos os formulários
**Template**: `@csrf` em formulários Blade
**Validação**: Automática pelo Laravel

### Validação de Dados

**Form Requests**: Classes personalizadas para validação
**Regras**: Definidas nos controladores
**Sanitização**: Automática pelo Eloquent

## Sistema de Assets

### Vite Configuration

**Arquivo**: `vite.config.js`

```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
```

### CSS

**Arquivo Principal**: `resources/css/app.css`

**Inclui**:
- Bootstrap 5
- Estilos personalizados
- CSS para impressão
- Responsividade

### JavaScript

**Arquivo Principal**: `resources/js/app.js`

**Funcionalidades**:
- Bootstrap JavaScript
- Validação de formulários
- Interações dinâmicas
- AJAX requests

## Configurações do Sistema

### Arquivo .env

**Configurações Principais**:
```env
# Aplicação
APP_NAME="Sistema Segurança Trabalho"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Banco de Dados
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=1234

# Email
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
```

### Cache e Performance

**Comandos de Otimização**:
```bash
# Cache de configuração
php artisan config:cache

# Cache de rotas
php artisan route:cache

# Cache de views
php artisan view:cache

# Otimização do autoloader
composer dump-autoload --optimize
```

## Logs e Debugging

### Sistema de Logs

**Localização**: `storage/logs/laravel.log`

**Níveis de Log**:
- `emergency`: Sistema inutilizável
- `alert`: Ação deve ser tomada imediatamente
- `critical`: Condições críticas
- `error`: Condições de erro
- `warning`: Condições de aviso
- `notice`: Condições normais mas significativas
- `info`: Mensagens informativas
- `debug`: Informações de debug

### Debug Mode

**Ativação**: `APP_DEBUG=true` no `.env`
**Funcionalidades**:
- Stack traces detalhados
- Informações de queries
- Variáveis de ambiente
- Tempo de execução

## Testes

### Estrutura de Testes

**Localização**: `tests/`

**Tipos**:
- `Feature/`: Testes de funcionalidades completas
- `Unit/`: Testes unitários de componentes

### Executar Testes

```bash
# Todos os testes
php artisan test

# Testes específicos
php artisan test --filter=AuthTest

# Com coverage
php artisan test --coverage
```

## Deployment

### Preparação para Produção

```bash
# Otimizações
composer install --no-dev --optimize-autoloader
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Permissões
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Configurações de Produção

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://seudominio.com

# Banco de produção
DB_HOST=servidor-postgres
DB_DATABASE=banco_producao
DB_USERNAME=usuario_producao
DB_PASSWORD=senha_segura

# Email de produção
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=email@empresa.com
MAIL_PASSWORD=senha_app
MAIL_ENCRYPTION=tls
```

## Manutenção e Monitoramento

### Comandos de Manutenção

```bash
# Limpeza de cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Limpeza de logs
php artisan log:clear

# Otimização do banco
php artisan migrate:status
php artisan db:show
```

### Backup

```bash
# Backup do banco
docker exec tcc-postgres pg_dump -U postgres postgres > backup_$(date +%Y%m%d).sql

# Backup de arquivos
tar -czf backup_files_$(date +%Y%m%d).tar.gz storage/ public/uploads/
```

## Extensibilidade

### Adicionando Novos Módulos

1. **Criar Model**: `php artisan make:model NomeModel -m`
2. **Criar Controller**: `php artisan make:controller NomeController`
3. **Criar Views**: Templates Blade em `resources/views/`
4. **Adicionar Rotas**: Definir em `routes/web.php`
5. **Executar Migração**: `php artisan migrate`

### Customizações

**CSS Personalizado**: Adicionar em `resources/css/custom.css`
**JavaScript Personalizado**: Adicionar em `resources/js/custom.js`
**Middleware Personalizado**: `php artisan make:middleware NomeMiddleware`
**Service Providers**: `php artisan make:provider NomeProvider`

## Considerações de Segurança

### Boas Práticas Implementadas

- **Autenticação**: Laravel Breeze com hash seguro
- **Autorização**: Middleware de proteção de rotas
- **CSRF Protection**: Tokens em todos os formulários
- **SQL Injection**: Eloquent ORM com prepared statements
- **XSS Protection**: Escape automático no Blade
- **Validação**: Form Requests e validação de dados
- **Criptografia**: Senhas com bcrypt
- **HTTPS**: Configuração para produção

### Recomendações Adicionais

- Manter Laravel atualizado
- Usar HTTPS em produção
- Configurar firewall adequado
- Monitorar logs de segurança
- Implementar rate limiting
- Backup regular dos dados
- Testes de segurança periódicos

Esta documentação cobre todos os aspectos técnicos do sistema, desde a arquitetura até a implementação de funcionalidades específicas, servindo como guia completo para desenvolvedores que precisem manter ou expandir o sistema.
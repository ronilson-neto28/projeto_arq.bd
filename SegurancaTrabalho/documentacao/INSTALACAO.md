# Guia de Instalação - Sistema de Segurança do Trabalho

## Pré-requisitos

Antes de instalar o sistema, certifique-se de ter os seguintes softwares instalados:

- **PHP 8.1 ou superior**
- **Composer** (gerenciador de dependências PHP)
- **Node.js 16 ou superior**
- **Docker e Docker Compose**
- **Git**
- **Servidor web** (Apache/Nginx) ou usar o servidor embutido do Laravel

## Passo a Passo da Instalação

### 1. Clonar o Repositório

```bash
git clone [URL_DO_REPOSITORIO]
cd SegurancaTrabalho
```

### 2. Instalar Dependências PHP

```bash
composer install
```

### 3. Instalar Dependências JavaScript

```bash
npm install
```

### 4. Configurar Arquivo de Ambiente

```bash
cp .env.example .env
```

Edite o arquivo `.env` com suas configurações:

```env
# Configurações da Aplicação
APP_NAME="Sistema Segurança Trabalho"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

# Configurações do Banco de Dados
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=1234

# Configurações de Email
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@segurancatrabalho.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 5. Gerar Chave da Aplicação

```bash
php artisan key:generate
```

### 6. Iniciar Serviços Docker

Inicie os containers do PostgreSQL e Mailpit:

```bash
docker-compose up -d
```

Isso criará:
- Container PostgreSQL na porta 5432
- Container Mailpit na porta 8025 (UI) e 1025 (SMTP)

### 7. Executar Migrações

```bash
php artisan migrate
```

### 8. Executar Seeders (Dados Iniciais)

```bash
php artisan db:seed
```

### 9. Criar Link Simbólico para Storage

```bash
php artisan storage:link
```

### 10. Compilar Assets

```bash
npm run build
```

### 11. Iniciar Servidor de Desenvolvimento

```bash
php artisan serve
```

### 12. Verificar Status dos Containers

```bash
docker-compose ps
```

## Acessos do Sistema

### URLs Principais

- **Sistema Principal**: http://localhost:8000
- **Mailpit (Emails)**: http://localhost:8025

### Usuários Padrão

Após executar os seeders, você terá os seguintes usuários:

- **Admin**: admin@sistema.com / password
- **Usuário**: user@sistema.com / password

## Configurações Adicionais

### Configuração de Email em Produção

Para ambiente de produção, configure um provedor de email real:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=seu-email@gmail.com
MAIL_PASSWORD=sua-senha-app
MAIL_ENCRYPTION=tls
```

### Configuração de Banco em Produção

```env
DB_CONNECTION=pgsql
DB_HOST=seu-servidor-postgres
DB_PORT=5432
DB_DATABASE=nome_do_banco
DB_USERNAME=usuario_postgres
DB_PASSWORD=senha_postgres
```

### Configurações de Cache e Sessão

```bash
# Limpar cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Otimizar para produção
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Estrutura de Pastas Importantes

```
SegurancaTrabalho/
├── app/
│   ├── Http/Controllers/     # Controladores
│   ├── Models/              # Modelos do banco
│   └── Mail/                # Classes de email
├── database/
│   ├── migrations/          # Migrações do banco
│   └── seeders/            # Dados iniciais
├── resources/
│   ├── views/              # Templates Blade
│   ├── css/                # Estilos CSS
│   └── js/                 # JavaScript
├── routes/
│   └── web.php             # Rotas da aplicação
└── public/                 # Arquivos públicos
```

## Solução de Problemas Comuns

### Erro de Permissão

```bash
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache
```

### Erro de Chave da Aplicação

```bash
php artisan key:generate
```

### Erro de Migração

```bash
php artisan migrate:fresh --seed
```

### Erro de Dependências

```bash
composer install --no-dev --optimize-autoloader
npm ci
```

## Backup e Manutenção

### Backup do Banco de Dados

```bash
docker exec tcc-postgres pg_dump -U postgres postgres > backup_$(date +%Y%m%d).sql
```

### Atualização do Sistema

```bash
git pull origin main
composer install
npm install
php artisan migrate
npm run build
php artisan cache:clear
```

## Suporte

Para suporte técnico ou dúvidas sobre a instalação, consulte a documentação técnica completa ou entre em contato com a equipe de desenvolvimento.
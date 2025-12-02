## Objetivo
- Eliminar completamente a dependência do banco relacional atual (PostgreSQL/MySQL/SQLite) para evitar conflitos ao introduzir o novo banco.
- Manter a aplicação executando (rotas e views) com respostas seguras, mesmo sem dados, até a nova integração.

## Escopo do que será removido
- Configuração e credenciais do banco em `.env` (chaves `DB_*`).
- Migrations e seeders em `database/migrations` e `database/seeders` (incluindo arquivos de dados CSV usados por seeders).
- Models Eloquent em `app/Models` relacionados às tabelas atuais (ex.: `Empresa`, `Funcionario`, `Cargo`, `Risco`, `TipoDeRisco`, `Telefone`, `Cnae`, `Encaminhamento`, `EncaminhamentoItem`, `Aso`, `CargoRisco`, `Exame`, `Procedimento`).
- Uso de `DB::`, `Schema::` e SQL bruto em controllers (ex.: `DashboardController`, `EmpresaController`, `FuncionarioController`, `EncaminhamentoController`, `CargoController`, `RiscoController`, `TipoDeRiscoController`).

## O que será preservado (temporário)
- Estrutura base do Laravel, `config/database.php` mínimo para não quebrar o boot do framework.
- Rotas em `routes/web.php` e views Blade, com dados vazios/placeholders.
- Autenticação pode ser temporariamente desativada ou mantida com respostas neutras; `User` só será removido se você preferir, pois o auth padrão depende de Eloquent.

## Mitigações para não quebrar a aplicação
- Trocar drivers dependentes de banco por alternativas: `SESSION_DRIVER=file`, `QUEUE_CONNECTION=sync`, `CACHE_DRIVER=file` no `.env`.
- Introduzir um flag `DISABLE_DB=true` para proteger qualquer caminho residual que tente acessar banco.
- Em controllers, retornar coleções vazias e status/flash messages informando que o banco está “desativado” até a nova integração.

## Passo a passo detalhado
1. Desativar dependências de banco no runtime
   - Atualizar `.env`: remover `DB_CONNECTION/DB_HOST/DB_PORT/DB_DATABASE/DB_USERNAME/DB_PASSWORD` e ajustar `SESSION_DRIVER`, `QUEUE_CONNECTION`, `CACHE_DRIVER` para não usar `database`.
   - Em `config/session.php` e `config/queue.php`, garantir que não há referência obrigatória ao driver `database`.
2. Remover artefatos de schema e carga inicial
   - Apagar `database/migrations/*` e `database/seeders/*` (incluindo `seeders/data/*`).
   - Remover `DatabaseSeeder` e referências a seeders.
3. Remover camada Eloquent
   - Apagar models em `app/Models` listados no escopo (exceto `User` se o auth permanecer por ora).
4. Neutralizar controllers
   - Remover imports `use Illuminate\Support\Facades\DB` e `use Illuminate\Database\Eloquent\Model`.
   - Substituir consultas por coleções vazias e mensagens informativas.
   - Remover `DB::transaction`, `DB::raw`, `whereRaw`, `selectRaw`, `Schema::hasColumn`.
5. Ajustar views
   - Garantir que listagens/paginação funcionem com dados vazios (exibir estados “sem dados”).
   - Remover componentes que assumem colunas/tabelas específicas.
6. Sanitizar configurações
   - Em `config/database.php`, manter somente um stub mínimo de conexão (ex.: `sqlite` memória) para não quebrar o boot, sem credenciais.
   - Remover/ignorar conexões `pgsql/mysql/sqlsrv/mariadb` não usadas.
7. Verificação
   - Rodar a aplicação localmente e navegar por rotas principais para garantir que nenhuma exceção de banco ocorre.
   - Confirmar que dashboard e listagens exibem estados vazios sem erros.

## Observações técnicas do seu código
- Há SQL específico de Postgres no `DashboardController` (funções `EXTRACT(...)`), e ajustes de `like/ilike` via `DB::getDriverName()` em `EmpresaController` e `FuncionarioController`; tudo isso será removido.
- Seeders usam `Schema::getColumnListing` para filtrar payload; essa dependência também sai.

## Próximo passo (após remoção)
- Introduzir uma nova camada de repositórios (interfaces) para o novo banco, com implementação plugável.
- Reativar funcionalidades gradualmente apontando para o novo backend.

Confirma que posso executar estes passos agora? 
# Projeto Base Laravel 12

Este projeto é uma base moderna e robusta para aplicações Laravel, preparada para atender desde projetos simples até arquiteturas complexas com controle de acesso (ACL), CI/CD e versionamento semântico.

## Recursos e Estrutura do Projeto

### 🔒 Autenticação e Segurança

-   Laravel Breeze com confirmação de e-mail
-   Laravel Auditing: rastreamento automático de alterações em modelos
-   Apenas uma sessão ativa por conta de usuário é permitida simultaneamente.

### 🎨 Front-end Moderno com Vite + Tailwind CSS

A base front-end utiliza:

-   Vite: bundler ultrarrápido com HMR (Hot Module Replacement) para uma experiência de desenvolvimento fluida e responsiva. Ideal para aplicações SPA ou híbridas.
-   Tailwind CSS: framework de utilitários altamente produtivo, com personalização via tema extendido e integração total com o modo escuro (`dark mode`) e classes responsivas.

O setup inclui:

-   Diretórios organizados para componentes reutilizáveis
-   Tema customizado com variáveis CSS e tokens globais
-   Arquitetura pensada para escalar em grandes projetos com consistência visual e performance

### 🌐 Internacionalização

-   Tradução completa para pt-BR com suporte aos pacotes oficiais do Laravel

### 📊 Relatórios e Visualizações Interativas

-   Geração de gráficos dinâmicos com Chart.js (linhas, barras, pizza, radar e mais)

## 🛠️ Código Limpo e Análise Estática

-   Laravel Pint é um formatador de código elegante baseado no PHP-CS-Fixer, configurado para seguir os padrões do Laravel.

## 🧠 Rector PHP (Refatoração automática)

-   Rector analisa e refatora seu código PHP automaticamente com base em regras configuradas (ideal para upgrades de versão, clean code e boas práticas).

## 🔍 Pail (Leitura interativa de logs)

-   Ferramenta CLI para visualização de logs do Laravel diretamente no terminal com destaque e filtros.

### 🚀 DevOps e Automação

-   Commits semânticos com Commitizen + Conventional Commits
-   Controle de versão automatizado com standard-version
-   Geração de changelog por versão
-   Estrutura CI/CD pronta para integração
-   Ambiente Docker completo para desenvolvimento e produção

Ideal como ponto de partida para qualquer tipo de projeto Laravel, com fácil personalização e expansão.

---

## Instruções de Instalação

### 2. Baixar o Projeto

Primeiro, clone o repositório para o seu ambiente local ou baixe o arquivo compactado.

### 3. Configurar o Ambiente

Duplicar o Arquivo de Configuração <br>
Copie o arquivo .env.example para um novo arquivo .env com o seguinte comando:

```bash
cp .env.example .env
```

### 4. Configurar Nome Sigla e Descrição da Aplicação

```bash
APP_NAME="Nome_App"
APP_DESCRIPTION="Description_App"
APP_SIGLA="Sigla_app"
```

### 5. Configurar a Conexão com o Banco de Dados

Abra o arquivo .env e configure a conexão com o banco de dados:
Substitua nome_da_base_de_dados, Usuario e Senha pelas informações corretas do seu banco de dados.

```bash
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=nome_da_base_de_dados
DB_USERNAME=Usuario
DB_PASSWORD=Senha
```

### 6. Altere as portas

```bash
NGINX_PORT=8697
MYSQL_PORT=3397
PHPMYADMIN_PORT=8097
VITE_PORT=5173
```

### 7. acesse a pasta e Suba os containers do projeto

```bash
docker compose up -d --build
```

### 8. Acesse o container app

```bash
docker-compose exec app bash
```

### 9. Se você já tiver o projeto baixado, pode rodar

```bash
composer update
npm install
```

### 10. Se não tiver instale as dependências PHP e JS:

execute o seguinte comando para instalar as dependências do Composer e npm :

```bash
composer install
npm install
```

### 11. Gerar a Chave de Aplicação

Execute o seguinte comando para gerar uma chave única para o seu aplicativo:

```bash
php artisan key:generate
```

### 12. Gerar a Chave de Aplicação

Execute o seguinte comando para gerar uma chave única para o seu aplicativo:

```bash
php artisan migrate --seed
```

### 13. Outras Configurações

Banco de Dados: O projeto está configurado para usar o banco MySQL, mas você pode alterar para outro banco de dados caso necessário, ajustando as variáveis no arquivo .env. <br>

Cache: Certifique-se de limpar o cache para garantir que todas as configurações mais recentes

```bash
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan clear-compiled
php artisan config:cache
php artisan clear:data
php artisan optimize:clear
```

## Comandos de Limpeza do Cache Laravel

| Comando          | O que limpa                           | Quando usar                      |
| ---------------- | ------------------------------------- | -------------------------------- |
| `cache:clear`    | Cache de dados geral                  | Após mudanças em cache de dados  |
| `view:clear`     | Cache das views compiladas Blade      | Após alterações em views         |
| `route:clear`    | Cache das rotas                       | Após mudanças em rotas           |
| `clear-compiled` | Arquivos compilados (antigo Laravel)  | Em versões antigas               |
| `config:cache`   | Cache das configs compiladas          | Em produção após mudanças config |
| `clear:data`     | custom (varia conforme implementação) | Verifique o código               |
| `optimize:clear` | Todos os caches otimizados juntos     | Para limpar tudo, reset geral    |

---

## Antes de fazer commits usar o Laravel Pint, Rector PHP

-   Laravel Pint

```bash
# Verificar código com detalhes (verbose)
./vendor/bin/pint -v

# Rodar em modo teste (não altera arquivos)
./vendor/bin/pint --test

# Corrigir automaticamente conforme padrão do Laravel
./vendor/bin/pint
```

-   Rector PHP (Refatoração automática)

```bash
# Simular mudanças (dry-run)
vendor/bin/rector process --dry-run

# Refatorar código automaticamente
vendor/bin/rector process
```

## ✍️ Commits semânticos com Commitizen

### Comando para iniciar um commit interativo:

```bash
git add .
npm run commit
```

Você verá um prompt como este:

```bash
? Selecione o tipo de alteração que você está submetendo:
❯ ✨ feat:     Nova funcionalidade
  🐛 fix:      Correção de bug
  📝 docs:     Documentação
  ♻️ refactor: Refatoração de código
  🎨 style:    Estilo e formatação
  ✅ test:     Testes
  🔧 chore:    Configuração ou tarefa de build
```

As mensagens geradas são padronizadas, exemplo:

`feat: adiciona tela de dashboard`

---

## 🚀 Versionamento automático com Standard Version

Use:

```bash
git add .
npm run commit               # cria commits padrão, interativo
npm run release:push         # gera release, tag, changelog e faz push automático
```

Este comando:

-   Atualiza `package.json` e `package-lock.json`
-   Gera ou atualiza o `CHANGELOG.md`
-   Cria um commit de release: `chore(release): 0.1.x`
-   Cria uma tag `v0.1.x`
-   Envia commits e tags para o repositório remoto

---

| Script                 | Mensagem custom?        | Gera versão | Git push | Tags   |
| ---------------------- | ----------------------- | ----------- | -------- | ------ |
| `npm run commit`       | ✅ Sim (via commitizen) | ❌ Não      | ❌ Não   | ❌ Não |
| `npm run release:msg`  | ✅ Sim (último commit)  | ✅ Sim      | ❌ Não   | ❌ Não |
| `npm run release:push` | ✅ Sim (último commit)  | ✅ Sim      | ✅ Sim   | ✅ Sim |

---

⚙️ Comandos para forçar versões Minor e Major manualmente
Além do fluxo automático baseado em commits, você pode forçar manualmente a criação de uma release minor ou major com estes comandos:

Forçar uma release minor (ex: 1.2.0)

```bash
npm run release:minor:push
```

Forçar uma release major (ex: 2.0.0)

```bash
npm run release:major:push
```

## 🧠 Sugestão de boas práticas

-   Utilize os tipos de commit: `feat`, `fix`, `chore`, `docs`, `refactor`, `test`, `style`
-   Sempre use `npm run release` para empacotar uma nova versão
-   Para publicar automaticamente no Git, use `npm run release:push`
-   Mantenha seu `CHANGELOG.md` atualizado com o fluxo automático
-   Realize `git pull` antes de rodar os scripts para evitar conflitos
-   Leitura Interativa de Logs

```bash
# Ver logs com detalhes
php artisan pail -v
```

---

## 🔗 Referências

-   [Laravel](https://laravel.com/docs/12.x/installation)
-   [Laravel Auditing](https://laravel-auditing.com)
-   [Laravel Breeze](https://github.com/laravel/breeze)
-   [Laravel Pint](https://laravel.com/docs/12.x/pint)
-   [Laravel Pail](https://laravel.com/docs/12.x/logging)
-   [docker](https://www.docker.com/)
-   [Commitizen](https://github.com/commitizen/cz-cli)
-   [Conventional Commits](https://www.conventionalcommits.org/)
-   [Standard Version](https://github.com/conventional-changelog/standard-version)
-   [Commitlint](https://github.com/conventional-changelog/commitlint)
-   [Módulo de linguagem pt-BR](https://github.com/lucascudo/laravel-pt-BR-localization)
-   [Vite](https://vite.dev/guide/)
-   [Tailwind](https://tailwindcss.com/docs/installation/using-vite)
-   [chartjs](https://www.chartjs.org/docs/latest/)
-   [RectorPHP](https://getrector.com/documentation)

Pacotes PHP / Laravel (via Composer)
Pacote Comando Descrição
Owen It Laravel Auditing composer require owen-it/laravel-auditing Auditoria de modelos: registra alterações de dados em logs.
Spatie Laravel Permission composer require spatie/laravel-permission Gerenciamento de roles e permissões.
Spatie Laravel Translatable composer require spatie/laravel-translatable Permite campos traduzíveis em modelos Eloquent.
Laravel Sanctum composer require laravel/sanctum Autenticação via API token.
Laravel Debugbar composer require barryvdh/laravel-debugbar --dev Debug e profiling do Laravel.
Spatie Laravel Backup composer require spatie/laravel-backup Backup completo do projeto, incluindo banco de dados e arquivos.
Laravel Octane composer require laravel/octane Para rodar aplicações Laravel de alta performance com Swoole ou RoadRunner.

Dica: Sempre verifique a versão do Laravel antes de instalar pacotes, para evitar incompatibilidade.

⚡ Pacotes Node / JS (via npm ou yarn)
Pacote Comando Descrição
Alpine.js npm install alpinejs Framework JS leve para manipulação reativa do DOM.
Vite npm install vite --save-dev Build tool moderno usado pelo Laravel para assets.
React / React DOM npm install react react-dom Se você estiver usando React no frontend.
Tailwind CSS npm install tailwindcss postcss autoprefixer --save-dev Framework CSS para estilização rápida.
PNPM npm install -g pnpm Gerenciador de pacotes alternativo ao npm.
Bun npm install -g bun Outra ferramenta de bundling e runtime JS.
Build de assets npm run build Comando para gerar arquivos finais de frontend (CSS/JS).
Rodar servidor dev npm run dev Rodar Vite em modo desenvolvimento com hot reload.

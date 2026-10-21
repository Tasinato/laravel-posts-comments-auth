<div align="center">

# Laravel Posts & Comments

**CRUD de postagens e comentários com autenticação e autorização**

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=flat-square&logo=php&logoColor=white)
![SQLite](https://img.shields.io/badge/SQLite-Database-003B57?style=flat-square&logo=sqlite&logoColor=white)
![Status](https://img.shields.io/badge/Status-Em%20desenvolvimento-64748B?style=flat-square)

**[Visão geral](#visão-geral) · [Permissões](#regras-de-acesso) · [Instalação](#instalação) · [Roadmap](#roadmap-de-desenvolvimento)**

</div>

---

## Visão geral

Aplicação web em **Laravel 12** para gerenciar postagens e comentários. O objetivo é permitir leitura pública, exigir autenticação para criar conteúdo e restringir edição e exclusão aos respectivos autores.

O projeto prioriza **simplicidade, organização, segurança e testes**, sem adicionar dependências desnecessárias.

> **Situação:** a estrutura inicial do Laravel 12 e o banco SQLite foram configurados. As funcionalidades de autenticação, posts, comentários e autorização descritas abaixo estão planejadas para as próximas etapas.

## Funcionalidades previstas

- Cadastro, login e logout de usuários.
- Listagem e visualização pública de postagens e comentários.
- Criação de posts e comentários por usuários autenticados.
- Edição e exclusão de conteúdo apenas por seu autor.
- Validação de formulários e proteção de operações via Laravel Policies.

## Tecnologias

| Tecnologia | Aplicação |
| :--- | :--- |
| **Laravel 12 / PHP 8.2+** | Backend e estrutura MVC |
| **SQLite** | Persistência dos dados |
| **Eloquent ORM** | Models e relacionamentos |
| **Blade** | Páginas renderizadas no servidor |
| **Laravel Policies** | Autorização por propriedade do recurso |
| **PHPUnit** | Testes automatizados |

## Regras de acesso

| Ação | Visitante | Usuário autenticado | Autor do conteúdo |
| :--- | :---: | :---: | :---: |
| Visualizar posts e comentários | ✅ | ✅ | ✅ |
| Criar posts e comentários | ❌ | ✅ | ✅ |
| Editar ou excluir conteúdo próprio | ❌ | ✅* | ✅ |
| Editar ou excluir conteúdo de terceiros | ❌ | ❌ | ❌ |

*O usuário autenticado só pode editar ou excluir quando também for o autor.*

> **Nota sobre RBAC:** o desafio menciona controle baseado em papéis (RBAC). Nesta implementação, o acesso de visitantes e usuários autenticados será combinado com uma regra de **propriedade do recurso (ownership)** nas Policies. Não se trata de um sistema completo de papéis e permissões persistidos em banco.

## Arquitetura

O projeto seguirá o padrão **MVC** do Laravel:

- **Models:** `User`, `Post` e `Comment`, com relacionamentos Eloquent.
- **Controllers:** tratamento das requisições, validação e operações CRUD.
- **Views:** páginas Blade para listagem, leitura, criação e edição.
- **Policies:** validação das permissões de alteração e exclusão.
- **Migrations:** definição versionada do esquema do banco.

### Relacionamentos previstos

```text
User 1 ----- N Post
User 1 ----- N Comment
Post 1 ----- N Comment
```

## Instalação

**Pré-requisitos:** PHP 8.2 ou superior, Composer, Node.js, npm e extensões PHP necessárias ao Laravel, incluindo SQLite/PDO.

```bash
# Clonar o repositório
git clone https://github.com/Tasinato/laravel-posts-comments-auth.git
cd laravel-posts-comments-auth

# Instalar dependências PHP
composer install

# Criar o arquivo de ambiente e a chave
cp .env.example .env
php artisan key:generate
```

No Windows PowerShell, use `Copy-Item .env.example .env` no lugar de `cp` se preferir o comando explícito do PowerShell.

Configure o `.env`:

```dotenv
DB_CONNECTION=sqlite
```

Crie o arquivo `database/database.sqlite`, caso ainda não exista. No PowerShell:

```powershell
New-Item -Path database/database.sqlite -ItemType File -Force
```

Depois execute:

```bash
php artisan migrate
npm install
php artisan serve
```

Em outro terminal, execute:

```bash
npm run dev
```

Acesse **http://127.0.0.1:8000**.

## Roadmap de desenvolvimento

**Progresso atual: 2 de 8 etapas (25,0%)**

- [x] **1. Configuração inicial**
  - [x] Criar o projeto Laravel 12.
  - [x] Configurar o SQLite.
  - [x] Preparar o ambiente de desenvolvimento.
  - [x] Validar a aplicação localmente e publicar o código inicial no GitHub.

- [x] **2. Autenticação de usuários**
  - [x] Implementar cadastro, login e logout.
  - [x] Proteger rotas que exigem autenticação.
  - [x] Validar autenticação baseada em sessões.
  - [x] Testar acesso de visitantes ao dashboard.

- [ ] **3. Banco de dados e relacionamentos**
  - Criar migrations para posts e comentários.
  - Configurar Models e relacionamentos Eloquent.
  - Executar e validar as migrations.

- [ ] **4. CRUD de posts**
  - Implementar listagem pública, visualização e criação.
  - Implementar edição e exclusão.
  - Criar as interfaces Blade.

- [ ] **5. CRUD de comentários**
  - Permitir comentar em postagens.
  - Exibir os comentários em cada post.
  - Implementar edição e exclusão e suas interfaces.

- [ ] **6. Autorização com Laravel Policies**
  - Implementar `PostPolicy` e `CommentPolicy`.
  - Restringir alterações ao autor do conteúdo.
  - Retornar HTTP 403 para ações não autorizadas.

- [ ] **7. Testes automatizados**
  - Testar leitura pública.
  - Testar criação por usuários autenticados.
  - Testar bloqueio de visitantes em ações protegidas.
  - Testar permissões do autor e bloqueio de terceiros.

- [ ] **8. Documentação e publicação**
  - Revisar README e instruções de instalação.
  - Documentar decisões técnicas.
  - Revisar o repositório e publicar a versão final.

### Estado atual

**Em desenvolvimento** · Próxima etapa: **autenticação de usuários**.

## Decisões técnicas

**Por que Laravel 12?** O framework organiza a aplicação com MVC e oferece recursos nativos de autenticação, validação, persistência e autorização.

**Por que SQLite?** É um banco relacional leve, adequado ao escopo do desafio e que não requer um servidor de banco de dados separado.

**Por que Blade?** Permite criar a interface no próprio Laravel sem a complexidade adicional de uma SPA.

**Por que Policies?** Centralizam as regras de autorização e impedem alterações em recursos de terceiros, inclusive em requisições feitas diretamente às rotas protegidas.

## Testes

Os testes automatizados poderão ser executados com:

```bash
php artisan test
```

---

<div align="center">

Projeto de demonstração técnica desenvolvido com **Laravel 12**.

</div>

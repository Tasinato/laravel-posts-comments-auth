# Laravel Posts & Comments

**CRUD de postagens e comentários com autenticação e autorização**

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=flat-square&logo=php&logoColor=white)
![SQLite](https://img.shields.io/badge/SQLite-Database-003B57?style=flat-square&logo=sqlite&logoColor=white)
![Status](https://img.shields.io/badge/Status-Em%20desenvolvimento-64748B?style=flat-square)

[Visão geral](#visão-geral) · [Funcionalidades](#funcionalidades) · [Permissões](#regras-de-acesso) · [Instalação](#instalação) · [Testes](#testes-automatizados) · [Roadmap](#roadmap-de-desenvolvimento)

---

## Visão geral

Aplicação web desenvolvida com **Laravel 12** para gerenciamento de postagens e comentários.

O projeto implementa autenticação baseada em sessões e controle de acesso por propriedade dos recursos (*ownership*).

As regras principais são:

- Qualquer visitante pode visualizar postagens e comentários.
- Usuários autenticados podem criar postagens e comentários.
- Apenas o autor pode editar ou excluir seu próprio conteúdo.
- Tentativas de alteração por terceiros retornam **HTTP 403 — Forbidden**.

O desenvolvimento prioriza **simplicidade, organização, segurança, legibilidade e testes automatizados**, utilizando recursos nativos do Laravel.

## Funcionalidades

- [x] Cadastro de usuários.
- [x] Login e logout.
- [x] Autenticação baseada em sessões.
- [x] Listagem pública de postagens.
- [x] Visualização individual de postagens e comentários.
- [x] Criação de postagens por usuários autenticados.
- [x] Edição e exclusão de postagens pelo autor.
- [x] Criação de comentários em postagens.
- [x] Edição e exclusão de comentários pelo autor.
- [x] Validação de formulários no backend.
- [x] Controle de autorização com Laravel Policies.
- [x] Testes de autorização de posts e comentários.
- [ ] Ampliação da cobertura de testes automatizados.
- [ ] Revisão final da documentação e publicação.

## Tecnologias

| Tecnologia | Aplicação |
| :--- | :--- |
| Laravel 12 | Framework backend e arquitetura MVC |
| PHP 8.2+ | Linguagem de programação |
| SQLite | Banco de dados relacional |
| Eloquent ORM | Persistência e relacionamentos |
| Blade | Renderização de interfaces |
| Laravel Policies | Regras de autorização |
| PHPUnit | Testes automatizados |
| Vite | Gerenciamento de assets frontend |
| Git / GitHub | Versionamento e publicação |

## Regras de acesso

| Ação | Visitante | Usuário autenticado |
| :--- | :---: | :---: |
| Visualizar posts e comentários | ✅ | ✅ |
| Criar posts e comentários | ❌ | ✅ |
| Editar conteúdo próprio | ❌ | ✅ |
| Excluir conteúdo próprio | ❌ | ✅ |
| Editar conteúdo de terceiros | ❌ | ❌ |
| Excluir conteúdo de terceiros | ❌ | ❌ |

A autenticação é necessária para criar ou modificar conteúdos. A autorização de edição e exclusão também exige que o usuário seja proprietário do recurso.

### RBAC e propriedade dos recursos

O desafio menciona controle de acesso baseado em papéis (*Role-Based Access Control — RBAC*).

Nesta implementação, foram utilizados dois mecanismos:

1. **Autenticação:** diferencia visitantes de usuários autenticados.
2. **Autorização por propriedade:** verifica se o usuário autenticado é o autor do recurso.

As verificações são centralizadas em `PostPolicy` e `CommentPolicy`.

Embora a abordagem atenda às regras de acesso solicitadas, **não implementa um sistema completo de RBAC**, com papéis e permissões persistidos em banco de dados.

## Arquitetura

A aplicação utiliza o padrão **Model–View–Controller (MVC)**.

| Camada | Responsabilidade |
| :--- | :--- |
| Models | Representação dos dados e relacionamentos |
| Controllers | Processamento de requisições e operações CRUD |
| Views | Renderização das páginas Blade |
| Policies | Centralização das decisões de autorização |
| Migrations | Versionamento do esquema do banco |
| Middleware | Proteção das rotas autenticadas |

### Relacionamentos

```text
User 1 ----- N Post
User 1 ----- N Comment
Post 1 ----- N Comment
```

Um usuário pode criar diversas postagens e comentários.

Uma postagem pode possuir diversos comentários.

Cada comentário pertence a uma postagem e possui um usuário autor.

### Autorização

As regras de alteração são definidas nas Policies por meio da comparação entre o identificador do usuário autenticado e o identificador do autor.

Exemplo:

```php
public function update(User $user, Post $post): bool
{
    return $user->id === $post->user_id;
}
```

Os controllers consultam as permissões utilizando o mecanismo de autorização do Laravel, como:

```php
Gate::authorize('update', $post);
```

Quando a autorização é negada, o Laravel retorna **HTTP 403 — Forbidden**.

A interface Blade também pode utilizar `@can` para mostrar as ações disponíveis ao usuário.

As verificações realizadas no backend permanecem obrigatórias, independentemente da visibilidade dos botões na interface.

## Instalação

### Pré-requisitos

- PHP 8.2 ou superior.
- Composer.
- Node.js e npm.
- Extensões PHP exigidas pelo Laravel, incluindo SQLite/PDO.
- Git.

### Clonar o repositório

```bash
git clone https://github.com/Tasinato/laravel-posts-comments-auth.git

cd laravel-posts-comments-auth
```

### Instalar dependências PHP

```bash
composer install
```

### Configurar o ambiente

Linux/macOS:

```bash
cp .env.example .env
```

Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Gerar a chave da aplicação:

```bash
php artisan key:generate
```

Configure o arquivo `.env`:

```dotenv
DB_CONNECTION=sqlite
```

Crie o banco de dados SQLite, se necessário.

Windows PowerShell:

```powershell
New-Item -Path database/database.sqlite -ItemType File -Force
```

Execute as migrations:

```bash
php artisan migrate
```

### Instalar dependências frontend

```bash
npm install
```

### Iniciar a aplicação

Terminal 1 — Laravel:

```bash
php artisan serve
```

Terminal 2 — Vite:

```bash
npm run dev
```

No PowerShell, caso exista bloqueio da execução do `npm.ps1`, utilize:

```powershell
npm.cmd run dev
```

Acesse:

**http://127.0.0.1:8000**

Alternativamente, para gerar os assets compilados:

```bash
npm run build
```

Nesse caso, não é necessário manter o servidor de desenvolvimento do Vite ativo para utilizar os assets compilados.

## Testes automatizados

O projeto utiliza **PHPUnit** para validar operações e regras de autorização.

### Executar todos os testes

```bash
php artisan test
```

### Testar a autorização de postagens

```bash
php artisan test --filter=PostAuthorizationTest
```

Cenários validados:

- Usuário não autorizado não pode atualizar uma postagem.
- Usuário não autorizado não pode excluir uma postagem.
- Autor pode atualizar sua postagem.
- Autor pode excluir sua postagem.

**Resultado registrado:** 4 testes aprovados, 10 asserções.

### Testar a autorização de comentários

```bash
php artisan test --filter=CommentAuthorizationTest
```

Cenários validados:

- Usuário não autorizado não pode atualizar um comentário.
- Usuário não autorizado não pode excluir um comentário.
- Autor pode atualizar seu comentário.
- Autor pode excluir seu comentário.

**Resultado registrado:** 4 testes aprovados, 10 asserções.

### Testes automatizados e Vite

O teste `tests/Feature/ExampleTest.php` utiliza o método `withoutVite()` para desativar a integração com o Vite durante sua execução:

```php
public function test_the_application_returns_a_successful_response(): void
{
    $this->withoutVite();

    $response = $this->get('/');

    $response->assertStatus(200);
}
```

Essa configuração permite que o teste seja executado sem depender do servidor frontend (`npm run dev`) ou dos assets compilados (`npm run build`).

**A página inicial continua sendo testada**, incluindo sua rota, a renderização da view Blade e a resposta HTTP 200.

A diretiva `withoutVite()` não desativa o teste da página: ela apenas dispensa a integração com os assets gerenciados pelo Vite.

A compilação e o carregamento de CSS e JavaScript devem ser verificados separadamente.

Essa decisão facilita a execução de testes automatizados em ambientes de CI/CD.

### Ambiente de testes

Os testes de banco de dados utilizam `RefreshDatabase` para isolar os cenários e evitar interferência nos registros do ambiente de desenvolvimento.

O ambiente de testes pode utilizar SQLite em memória, conforme a configuração do PHPUnit.

## Roadmap de desenvolvimento

**Progresso confirmado: 6 de 8 etapas concluídas (75%).**

A etapa 6 encontra-se em validação final.

### 1. Configuração inicial

- [x] Criar o projeto Laravel 12.
- [x] Configurar o SQLite.
- [x] Preparar o ambiente de desenvolvimento.
- [x] Validar a aplicação localmente.
- [x] Publicar o código inicial no GitHub.

### 2. Autenticação de usuários

- [x] Implementar cadastro, login e logout.
- [x] Proteger rotas que exigem autenticação.
- [x] Validar autenticação baseada em sessões.
- [x] Testar acesso de visitantes ao dashboard.

### 3. Banco de dados e relacionamentos

- [x] Criar migrations para posts e comentários.
- [x] Configurar Models e relacionamentos Eloquent.
- [x] Executar e validar as migrations.
- [x] Validar registros utilizando o Tinker.
- [x] Confirmar os relacionamentos entre User, Post e Comment.

### 4. CRUD de posts

- [x] Implementar listagem pública.
- [x] Implementar criação por usuários autenticados.
- [x] Implementar visualização individual.
- [x] Implementar edição e exclusão pelo autor.
- [x] Criar interfaces Blade.
- [x] Validar acesso público e proteção de rotas.
- [x] Testar autorização de PUT e DELETE com PHPUnit.

### 5. CRUD de comentários

- [x] Permitir criar comentários em postagens.
- [x] Exibir comentários nas postagens.
- [x] Implementar edição e exclusão pelo autor.
- [x] Criar interface Blade para edição.
- [x] Validar HTTP 403 para edição por terceiros.
- [x] Validar autorização de PUT e DELETE com PHPUnit.
- [x] Executar 4 testes com 10 asserções.

### 6. Autorização com Laravel Policies

- [x] Implementar `PostPolicy` e `CommentPolicy`.
- [x] Definir permissões por propriedade do recurso (*ownership*).
- [x] Centralizar a autorização utilizando `Gate::authorize()`.
- [x] Restringir edição e exclusão aos respectivos autores.
- [x] Retornar HTTP 403 para operações não autorizadas.
- [x] Validar os testes de autorização de posts (4 testes, 10 asserções).
- [x] Validar os testes de autorização de comentários (4 testes, 10 asserções).
- [x] Corrigir o teste da página inicial utilizando `withoutVite()`.
- [x] Confirmar a suíte completa: **10 testes aprovados, 22 asserções e nenhuma falha**.
- [x] Revisar a integração das Policies nos controllers e nas views.


### 7. Testes automatizados

- [ ] Ampliar os testes de leitura pública.
- [ ] Testar criação de conteúdo por usuários autenticados.
- [ ] Testar bloqueio de visitantes em rotas protegidas.
- [ ] Complementar os cenários de autorização.
- [ ] Validar a suíte completa sem falhas.

### 8. Documentação e publicação

- [ ] Revisar README e instruções de instalação.
- [ ] Documentar decisões técnicas.
- [ ] Revisar arquivos versionados.
- [ ] Publicar versão final no GitHub.

### Estado atual

**Em desenvolvimento — Etapa: 7.**

Próxima atividade: Testes automatizados, com ampliação da cobertura para leitura pública, autenticação, criação de conteúdo, validação de formulários e proteção de rotas.

## Decisões técnicas

**Por que Laravel 12?**

Oferece estrutura MVC e recursos nativos de autenticação, validação, persistência e autorização, reduzindo a necessidade de bibliotecas adicionais.

**Por que SQLite?**

É um banco de dados relacional leve, adequado ao escopo do projeto e que não necessita de um servidor de banco separado.

**Por que Blade?**

Permite desenvolver a interface integrada ao Laravel, evitando a complexidade adicional de uma aplicação SPA.

**Por que Laravel Policies?**

Centralizam regras de autorização, facilitam manutenção e testes, e impedem alterações de recursos por usuários não autorizados.

**Por que PHPUnit?**

Permite verificar automaticamente os requisitos funcionais e identificar regressões durante alterações no código.

**Por que `withoutVite()` em testes de renderização?**

Para evitar que testes de resposta HTTP dependam da disponibilidade de um servidor frontend ou de uma compilação anterior de assets.

---

Projeto de demonstração técnica desenvolvido com **Laravel 12, PHP, SQLite e Blade**.
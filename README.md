# Laravel Posts & Comments

**CRUD de postagens e comentários com autenticação e autorização por autoria**

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=flat-square&logo=php&logoColor=white)
![SQLite](https://img.shields.io/badge/SQLite-Database-003B57?style=flat-square&logo=sqlite&logoColor=white)
![Status](https://img.shields.io/badge/Status-Frontend%20em%20evolu%C3%A7%C3%A3o-64748B?style=flat-square)

[Visão geral](#visão-geral) · [Funcionalidades](#funcionalidades) · [Regras de acesso](#regras-de-acesso) · [Instalação](#instalação) · [Testes](#testes-automatizados) · [Roadmap](#roadmap-de-desenvolvimento)

## Visão geral

Aplicação web em **Laravel 12** para gerenciamento de postagens e comentários. Qualquer pessoa pode ler conteúdo, usuários autenticados podem publicar, e **somente o autor** pode editar ou excluir cada postagem ou comentário.

A interface principal do CRUD utiliza **Blade**, com estilos CSS modulares e melhorias de responsividade e acessibilidade. A integração com **React + TypeScript + Inertia.js** está iniciada, com layout compartilhado e uma listagem experimental de postagens. A migração do CRUD para React ainda não está concluída.

Esta versão está na branch [feature/react-inertia-frontend](https://github.com/Tasinato/laravel-posts-comments-auth/tree/feature/react-inertia-frontend). O link do GitHub permite consultar o código; não representa uma aplicação hospedada.

## Funcionalidades

- [x] Cadastro, login e logout com autenticação por sessão.
- [x] Listagem pública e visualização de postagens e comentários.
- [x] Criação de postagens e comentários por usuários autenticados.
- [x] Edição e exclusão restritas ao respectivo autor.
- [x] Validação de formulários no backend.
- [x] Autorização centralizada com `PostPolicy` e `CommentPolicy`.
- [x] Testes automatizados de acesso público, criação, validação e autorização.
- [x] CSS modular para layout, navegação, botões, alertas, cards e formulários.
- [x] Campos com rótulos e associações ARIA condicionais para mensagens de validação.
- [x] Link para pular ao conteúdo e indicação de foco por teclado.
- [x] Integração inicial React + TypeScript + Inertia.js.
- [ ] Migrar as páginas de autenticação e o CRUD completo para React/Inertia.

## Tecnologias

| Tecnologia | Utilização |
| --- | --- |
| Laravel 12 e PHP 8.2+ | Backend e arquitetura MVC |
| SQLite | Persistência relacional sem servidor dedicado |
| Eloquent ORM | Models e relacionamentos |
| Blade | Interface atual de autenticação e CRUD |
| React, TypeScript e Inertia.js | Integração e páginas experimentais de frontend |
| Tailwind CSS 4 e CSS modular | Estilos e responsividade |
| Laravel Policies e Gate | Autorização por autoria |
| PHPUnit | Testes automatizados |
| Vite | Assets CSS e JavaScript |
| Git e GitHub | Controle de versão |

## Regras de acesso

| Ação | Visitante | Usuário autenticado |
| --- | :---: | :---: |
| Visualizar postagens e comentários | ✅ | ✅ |
| Criar postagens e comentários | ❌ | ✅ |
| Editar ou excluir conteúdo próprio | ❌ | ✅ |
| Editar ou excluir conteúdo de terceiros | ❌ | ❌ |

A criação e as alterações exigem autenticação. Para editar ou excluir, as Policies verificam também a autoria. Acesso indevido de um usuário autenticado retorna **HTTP 403 (Forbidden)**; visitantes são direcionados ao login pelas rotas protegidas.

### RBAC e propriedade dos recursos

O enunciado do desafio menciona **RBAC** (*Role-Based Access Control*), mas as regras descritas exigem essencialmente autenticação e autorização por propriedade do recurso (*ownership*).

Por isso, foi escolhida uma implementação **sem papéis e permissões persistidos no banco**: o middleware `auth` controla as ações de usuários autenticados, enquanto `PostPolicy` e `CommentPolicy` garantem que apenas cada autor altere seu conteúdo. Trata-se de **autorização por autoria, não de um RBAC formal**.

Essa decisão preserva as regras do enunciado, inclusive a proibição de edição por terceiros, e evita adicionar administração de papéis que não foi solicitada. Caso se torne requisito futuro, a solução poderá evoluir para RBAC sem substituir a autenticação e as Policies atuais.

## Arquitetura

O projeto usa o padrão **MVC** do Laravel:

- **Models:** `User`, `Post` e `Comment` com relacionamentos Eloquent.
- **Controllers:** processamento das requisições, validação e operações CRUD.
- **Views:** páginas Blade para autenticação, painel e CRUD.
- **Frontend React:** entrada em `resources/js/app.tsx`, layout em `resources/js/Layouts/AppLayout.tsx` e páginas em `resources/js/Pages`.
- **CSS:** entrada em `resources/css/app.css`, com módulos em `base`, `components` e `pages`.
- **Policies:** verificação centralizada da autoria para editar e excluir.
- **Middleware:** restrição das rotas de escrita a usuários autenticados.
- **Migrations:** versionamento do esquema SQLite.

```text
User 1 ----- N Post
User 1 ----- N Comment
Post 1 ----- N Comment
```

Exemplo de autorização em `PostPolicy`:

```php
public function update(User $user, Post $post): bool
{
    return $user->id === $post->user_id;
}
```

Os controllers verificam as permissões, por exemplo, com `Gate::authorize('update', $post)`. Os botões podem ser exibidos condicionalmente com `@can`, mas a proteção efetiva ocorre **no backend**.

## Instalação

### Pré-requisitos

PHP 8.2+, Composer, Node.js compatível com o Vite instalado (veja a exigência abaixo), npm, Git e extensões PHP exigidas pelo Laravel, incluindo SQLite/PDO. As dependências são registradas em `composer.json`, `package.json` e seus arquivos de lock.

A versão do Vite instalada durante esta revisão é `8.3.5`, que declara Node.js `^20.19.0 || >=22.12.0`. Use uma versão que satisfaça esse requisito.

### Clonar e instalar

```bash
git clone https://github.com/Tasinato/laravel-posts-comments-auth.git
cd laravel-posts-comments-auth
git switch feature/react-inertia-frontend
composer install
npm ci
```

Configure o ambiente:

```bash
cp .env.example .env
php artisan key:generate
```

No **Windows PowerShell**, em vez de `cp`, você pode usar `Copy-Item .env.example .env`.

No `.env`, configure:

```dotenv
DB_CONNECTION=sqlite
```

Se o arquivo do banco ainda não existir, crie `database/database.sqlite`. No PowerShell:

```powershell
if (-not (Test-Path database/database.sqlite)) {
    New-Item -Path database/database.sqlite -ItemType File
}
```

Depois execute:

```bash
php artisan migrate
```

### Iniciar a aplicação

Terminal 1:

```bash
php artisan serve
```

Terminal 2:

```bash
npm run dev
```

No Windows PowerShell, se `npm.ps1` for bloqueado, execute `npm.cmd run dev`.

Acesse **http://127.0.0.1:8000**.

Como alternativa ao servidor Vite, gere os arquivos estáticos com `npm run build` (ou `npm.cmd run build` no PowerShell).

## Páginas disponíveis

| Endereço local | Finalidade | Acesso |
| --- | --- | --- |
| `/` | Página inicial | Público |
| `/posts` | Listagem Blade | Público |
| `/posts/{id}` | Postagem e comentários | Leitura pública |
| `/register` e `/login` | Cadastro e entrada | Visitante |
| `/dashboard` | Painel do usuário | Autenticado |
| `/posts/create` | Nova postagem | Autenticado |
| `/posts/{id}/edit` | Editar postagem | Autor |
| `/comments/{id}/edit` | Editar comentário | Autor |
| `/react-test` | Teste da integração React/Inertia | Público, experimental |
| `/react-posts-test` | Listagem React/Inertia | Público, experimental |

Substitua `{id}` por um registro existente no seu banco. Cadastre sua própria conta para criar conteúdo; a instalação não pressupõe credenciais de demonstração.

## Testes automatizados

Execute todos os testes com:

```bash
php artisan test
```

Também é possível executar os grupos individualmente:

```bash
php artisan test --filter=PostAuthorizationTest
php artisan test --filter=CommentAuthorizationTest
php artisan test --filter=PostPublicAccessTest
php artisan test --filter=PostCreationTest
php artisan test --filter=CommentCreationTest
php artisan test --filter=GuestAuthorizationTest
```

Os testes cobrem leitura pública, criação autenticada, validação de campos obrigatórios, bloqueio de visitantes e edição/exclusão restritas ao autor. Os testes com dados usam `RefreshDatabase` e o ambiente de teste configurado no `phpunit.xml`.

**Resultado da suíte completa informado pela execução local em 01/11/2026:** **21 testes aprovados, 58 asserções e nenhuma falha** (`php artisan test`).

### Vite nos testes

Testes que renderizam views Blade sem verificar CSS/JavaScript podem utilizar `$this->withoutVite()` para não depender de um servidor Vite nem de um manifesto compilado:

```php
public function test_the_application_returns_a_successful_response(): void
{
    $this->withoutVite();

    $response = $this->get('/');

    $response->assertStatus(200);
}
```

Isso **não ignora o teste da página**: rota, resposta HTTP e renderização Blade continuam sendo exercitadas. A compilação e a execução dos assets frontend devem ser verificadas separadamente.


## Roadmap de desenvolvimento

As etapas 1 a 8 registram o histórico do projeto. A etapa 9 reúne as melhorias atuais da interface e distingue implementação de validação. A migração completa para React/Inertia permanece em desenvolvimento.

### 1. Configuração inicial
- [x] Criar o projeto Laravel 12, configurar SQLite e ambiente local.
- [x] Validar a aplicação e publicar o código inicial.

### 2. Autenticação
- [x] Implementar cadastro, login e logout.
- [x] Proteger rotas e validar sessões.

### 3. Banco de dados e relacionamentos
- [x] Criar migrations e relacionamentos Eloquent.
- [x] Validar dados e relacionamentos no Tinker.

### 4. CRUD de postagens
- [x] Implementar listagem, visualização, criação, edição e exclusão.
- [x] Validar propriedade das postagens com testes de autorização.

### 5. CRUD de comentários
- [x] Implementar criação, listagem, edição e exclusão.
- [x] Validar restrições de autoria e HTTP 403.

### 6. Laravel Policies
- [x] Implementar `PostPolicy` e `CommentPolicy`.
- [x] Centralizar a autorização com Gate e verificar restrições de autoria.
- [x] Reexecutar os testes após a refatoração.

### 7. Testes automatizados
- [x] Cobrir leitura pública, criação autenticada e campos obrigatórios.
- [x] Cobrir bloqueio de visitantes e ações de terceiros.
- [x] Executar os novos testes com sucesso.

### 8. Documentação e publicação
- [x] Revisar o README e registrar decisões técnicas.
- [x] Confirmar a suíte completa atualizada sem falhas: 21 testes, 58 asserções.
- [x] Revisar os arquivos versionados e o `.env.example` e realizar busca básica de padrões de segredos.
- [x] Publicar a versão anterior de código e testes no GitHub (commit `be968c1`), conforme o histórico documentado.
- [x] Publicar a integração inicial React/Inertia e os estilos modulares na branch `feature/react-inertia-frontend` (commit `ffb7377`).
- [x] Publicar a revisão anterior do README, incluindo autoria e declaração de uso de IA.

### 9. Usabilidade, Interface e Acessibilidade

#### 9.1. Implementado

- CRUD Blade com autenticação por sessão e autorização por autoria.
- Cabeçalho com navegação para visitantes e usuários autenticados.
- CSS modular para páginas, formulários, cards, botões e mensagens.
- Títulos principais em `h1` nos formulários e no painel.
- Rótulos de campos e mensagens de erro associadas por `aria-describedby` e `aria-invalid` quando há erro.
- Link para pular ao conteúdo, foco visível e tratamento de movimento reduzido.
- Botões de edição e exclusão condicionados às Policies.
- Integração inicial React/Inertia e listagem experimental.

#### 9.2. Verificações realizadas em 01/11/2026

- Inspeção visual de início, listagem, detalhes, cadastro e formulários em larguras selecionadas de desktop e celular; a página de detalhes também foi conferida em tablet.
- Conferência do painel no desktop.
- Conferência da visibilidade de ações com autor e outro usuário: cada usuário vê ações somente para seu próprio conteúdo.
- Atualização de comentário confirmada por mensagem de sucesso.
- Validação nativa de campo obrigatório confirmada no formulário de nova postagem.
- Compilação dos assets concluída durante a revisão com `npm run build`.
- Suíte Laravel: **21 testes aprovados e 58 asserções**.

#### 9.3. Verificações e melhorias pendentes

- Conferir visualmente os erros retornados pelo Laravel e suas associações ARIA em execução; o aviso nativo de campo obrigatório não substitui essa verificação.
- Ampliar a navegação manual por teclado, os testes de zoom e a cobertura de diferentes tamanhos de tela.
- Avaliar com Lighthouse/axe e leitor de tela, como NVDA.
- Revisar os critérios aplicáveis da WCAG 2.2 A e AA. Ainda não há declaração de conformidade completa.
- Completar a migração do CRUD e da autenticação para React/Inertia, preservando as Policies.

As verificações visuais são amostrais e não substituem uma auditoria de acessibilidade ou testes completos de navegador.

## Decisões técnicas

**Por que Laravel 12?** Oferece MVC, validação, autenticação, persistência e autorização nativas, reduzindo código adicional.

**Por que SQLite?** É relacional, leve e suficiente para um desafio pequeno, sem exigir infraestrutura de banco dedicada.

**Por que Blade e React/Inertia coexistem?** Blade mantém o CRUD atual funcionando enquanto a integração React/Inertia evolui em páginas experimentais. A migração deve preservar a autenticação, as rotas de negócio e as Policies.

**Por que Policies em vez de um RBAC com tabelas de papéis?** O enunciado prioriza simplicidade e exige somente restrição por autoria. Policies expressam essa regra diretamente e são testáveis. A solução não deve ser apresentada como RBAC formal.

**Por que PHPUnit?** Permite validar cenários de acesso e detectar regressões após refatorações.

**Por que `withoutVite()` nos testes HTTP?** Isola a verificação da rota e da view em relação ao processo de compilação frontend.

## Autoria

**Desenvolvido por Rafael Tasinato**  
Engenheiro de Software | Inteligência Artificial Aplicada | Automação

- **GitHub:** [Tasinato](https://github.com/Tasinato)
- **LinkedIn:** [Rafael Tasinato](https://www.linkedin.com/in/rafael-tasinato)

### Uso de Inteligência Artificial no desenvolvimento

O projeto foi desenvolvido com o apoio do **ChatGPT (OpenAI)** como ferramenta de assistência técnica durante o planejamento, a estruturação da aplicação, a implementação, a análise de erros, a elaboração de testes automatizados e a documentação.

A ferramenta foi utilizada como suporte ao processo de desenvolvimento. A execução local, a verificação dos resultados e a responsabilidade técnica pela entrega são do autor.

---

**Projeto de demonstração técnica com Laravel 12, PHP, SQLite, Blade e integração inicial React + Inertia.js.**

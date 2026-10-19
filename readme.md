# CRUD de Posts e Comentários — Laravel 12

## Descrição

Aplicação web desenvolvida em Laravel 12 para gerenciamento de postagens e comentários, utilizando autenticação e autorização para controlar o acesso às operações.

## Tecnologias

- PHP e Laravel 12
- SQLite
- Eloquent ORM
- Blade
- Laravel Policies

## Funcionalidades

- Cadastro, login e logout
- Listagem pública de postagens
- Visualização pública de comentários
- Criação de postagens por usuários autenticados
- Criação de comentários por usuários autenticados
- Edição e exclusão de conteúdo pelo respectivo autor

## Decisões técnicas

**Por que Laravel 12?**

O Laravel oferece uma arquitetura MVC organizada e funcionalidades nativas de autenticação, validação, persistência e autorização, facilitando a manutenção do projeto.

**Por que SQLite?**

É um banco de dados relacional leve, sem necessidade de configurar um servidor separado. Atende às necessidades de um projeto de pequeno porte.

**Por que Blade?**

Permite construir interfaces HTML diretamente no Laravel, sem adicionar a complexidade de um framework frontend JavaScript.

**Por que Laravel Policies?**

As Policies centralizam as regras de autorização e impedem que usuários modifiquem ou excluam recursos pertencentes a outras pessoas.

**Como funciona o controle de permissões?**

A autenticação determina quem pode criar conteúdo. A autorização verifica a propriedade de cada postagem ou comentário antes de permitir alterações e exclusões. Não são necessárias tabelas adicionais de papéis para os requisitos atuais.

## Testes

O projeto deve incluir testes de acesso público, autenticação e autorização para garantir o cumprimento das regras de negócio.

## Arquitetura

O projeto segue a arquitetura MVC do Laravel, utilizando Models, Views, Controllers, migrations e Policies.



Qual o nome do repositorio adequado?

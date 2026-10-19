<div align="center">

# Laravel Posts & Comments

**CRUD de postagens e coment├írios com autentica├º├úo e autoriza├º├úo**

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=flat-square&logo=php&logoColor=white)
![SQLite](https://img.shields.io/badge/SQLite-Database-003B57?style=flat-square&logo=sqlite&logoColor=white)
![Status](https://img.shields.io/badge/Status-Em%20desenvolvimento-64748B?style=flat-square)

**[Vis├úo geral](#vis├úo-geral) ┬À [Permiss├Áes](#regras-de-acesso) ┬À [Instala├º├úo](#instala├º├úo) ┬À [Roadmap](#roadmap-de-desenvolvimento)**

</div>

---

## Vis├úo geral

Aplica├º├úo web desenvolvida em **Laravel 12** para gerenciar postagens e coment├írios. O sistema permite leitura p├║blica e exige autentica├º├úo para criar conte├║do. A edi├º├úo e a exclus├úo de posts e coment├írios s├úo restritas aos respectivos autores.

O projeto prioriza **simplicidade, organiza├º├úo, seguran├ºa e testes**, sem adicionar depend├¬ncias desnecess├írias.

## Funcionalidades

- Cadastro, login e logout de usu├írios.
- Listagem e visualiza├º├úo p├║blica de postagens e coment├írios.
- Cria├º├úo de posts e coment├írios por usu├írios autenticados.
- Edi├º├úo e exclus├úo de conte├║do apenas por seu autor.
- Valida├º├úo de formul├írios e prote├º├úo de opera├º├Áes via Laravel Policies.

## Tecnologias

| Tecnologia | Aplica├º├úo |
| :--- | :--- |
| **Laravel 12 / PHP 8.2+** | Backend e estrutura MVC |
| **SQLite** | Persist├¬ncia dos dados |
| **Eloquent ORM** | Models e relacionamentos |
| **Blade** | P├íginas renderizadas no servidor |
| **Laravel Policies** | Autoriza├º├úo por propriedade do recurso |
| **PHPUnit** | Testes automatizados |

## Regras de acesso

| A├º├úo | Visitante | Usu├írio autenticado | Autor do conte├║do |
| :--- | :---: | :---: | :---: |
| Visualizar posts e coment├írios | Ô£à | Ô£à | Ô£à |
| Criar posts e coment├írios | ÔØî | Ô£à | Ô£à |
| Editar ou excluir conte├║do pr├│prio | ÔØî | Ô£à* | Ô£à |
| Editar ou excluir conte├║do de terceiros | ÔØî | ÔØî | ÔØî |

*O usu├írio autenticado s├│ pode editar ou excluir quando tamb├®m for o autor.*

> **Nota sobre RBAC:** o desafio menciona controle baseado em pap├®is (RBAC). Nesta implementa├º├úo, o acesso de visitantes e usu├írios autenticados ├® combinado com uma regra de **propriedade do recurso (ownership)** nas Policies. N├úo se trata de um sistema completo de pap├®is e permiss├Áes persistidos em banco.

## Arquitetura

O projeto utiliza o padr├úo **MVC** do Laravel:

- **Models:** `User`, `Post` e `Comment`, com relacionamentos Eloquent.
- **Controllers:** tratamento das requisi├º├Áes, valida├º├úo e opera├º├Áes CRUD.
- **Views:** p├íginas Blade para listagem, leitura, cria├º├úo e edi├º├úo.
- **Policies:** valida├º├úo das permiss├Áes de altera├º├úo e exclus├úo.
- **Migrations:** defini├º├úo versionada do esquema do banco.

### Relacionamentos

```text
User 1 ÔöÇÔöÇÔöÇÔöÇÔöÇ N Post
User 1 ÔöÇÔöÇÔöÇÔöÇÔöÇ N Comment
Post 1 ÔöÇÔöÇÔöÇÔöÇÔöÇ N Comment
```

## Instala├º├úo

> **Em prepara├º├úo:** os comandos abaixo representam o fluxo previsto; ser├úo validados ap├│s a implementa├º├úo da autentica├º├úo, das migrations e das interfaces.

**Pr├®-requisitos:** PHP 8.2 ou superior, Composer e extens├Áes PHP necess├írias ao Laravel, incluindo SQLite/PDO.

```bash
# Clonar o reposit├│rio
 git clone https://github.com/SEU-USUARIO/laravel-posts-comments-rbac.git
 cd laravel-posts-comments-rbac

# Instalar depend├¬ncias PHP
 composer install

# Configurar o ambiente
 cp .env.example .env
 php artisan key:generate
```

No Windows PowerShell, use `Copy-Item .env.example .env` no lugar de `cp`.

Configure o arquivo `.env` para usar SQLite:

```dotenv
DB_CONNECTION=sqlite
```

Crie `database/database.sqlite` caso o arquivo ainda n├úo exista. Depois:

```bash
php artisan migrate
php artisan serve
```

Acesse **http://127.0.0.1:8000**. Se a interface utilizar assets compilados pelo Vite, ser├í necess├írio tamb├®m executar `npm install` e `npm run dev` em outro terminal.

## Roadmap de desenvolvimento

**Progresso atual: 1 de 8 etapas (12,5%)**

- [X] **1. Configura├º├úo inicial**
  - Criar o projeto Laravel 12.
  - Configurar o SQLite.
  - Preparar o ambiente de desenvolvimento.

- [ ] **2. Autentica├º├úo de usu├írios**
  - Implementar cadastro, login e logout.
  - Proteger rotas que exigem autentica├º├úo.

- [ ] **3. Banco de dados e relacionamentos**
  - Criar migrations para posts e coment├írios.
  - Configurar Models e relacionamentos Eloquent.
  - Executar e validar as migrations.

- [ ] **4. CRUD de posts**
  - Implementar listagem p├║blica, visualiza├º├úo e cria├º├úo.
  - Implementar edi├º├úo e exclus├úo.
  - Criar as interfaces Blade.

- [ ] **5. CRUD de coment├írios**
  - Permitir comentar em postagens.
  - Exibir os coment├írios em cada post.
  - Implementar edi├º├úo e exclus├úo e suas interfaces.

- [ ] **6. Autoriza├º├úo com Laravel Policies**
  - Implementar `PostPolicy` e `CommentPolicy`.
  - Restringir altera├º├Áes ao autor do conte├║do.
  - Retornar HTTP 403 para a├º├Áes n├úo autorizadas.

- [ ] **7. Testes automatizados**
  - Testar leitura p├║blica.
  - Testar cria├º├úo por usu├írios autenticados.
  - Testar bloqueio de visitantes em a├º├Áes protegidas.
  - Testar permiss├Áes do autor e bloqueio de terceiros.

- [ ] **8. Documenta├º├úo e publica├º├úo**
  - Revisar README e instru├º├Áes de instala├º├úo.
  - Documentar decis├Áes t├®cnicas.
  - Revisar o reposit├│rio e publicar a vers├úo final.

### Estado atual

**Em desenvolvimento** ┬À Pr├│xima etapa: **configura├º├úo inicial do Laravel 12 e SQLite**.

## Decis├Áes t├®cnicas

**Por que Laravel 12?** O framework organiza a aplica├º├úo com MVC e oferece recursos nativos de autentica├º├úo, valida├º├úo, persist├¬ncia e autoriza├º├úo.

**Por que SQLite?** ├ë um banco relacional leve, adequado ao escopo do desafio e que n├úo requer um servidor de banco de dados separado.

**Por que Blade?** Permite criar a interface no pr├│prio Laravel sem a complexidade adicional de uma SPA.

**Por que Policies?** Centralizam as regras de autoriza├º├úo e impedem altera├º├Áes em recursos de terceiros, inclusive em requisi├º├Áes feitas diretamente ├ás rotas protegidas.

## Testes

Ap├│s a implementa├º├úo, os testes poder├úo ser executados com:

```bash
php artisan test
```

---

<div align="center">

Projeto de demonstra├º├úo t├®cnica desenvolvido com **Laravel 12**.

</div>

# Autenticação e recuperação de senha

## Fluxo implementado

O link **Esqueci minha senha** da tela de login abre um formulário de e-mail. O Password Broker nativo do Laravel gera um token aleatório, armazena somente o hash na tabela password_reset_tokens e envia uma notificação em português. O token expira em 60 minutos, pode ser substituído após o intervalo mínimo de 60 segundos e é removido após uma redefinição válida.

O formulário exige e-mail, token e nova senha confirmada. Cadastro e redefinição exigem pelo menos 12 caracteres e respeitam o limite de 72 bytes do bcrypt, inclusive para Unicode. Não há obrigação artificial de combinar símbolos ou maiúsculas; frases longas e gerenciadores são compatíveis. Senhas existentes mais curtas continuam válidas no login.

A confirmação de solicitação é a mesma para conta existente, inexistente ou aguardando novo envio. Erros de transporte SMTP geram um aviso operacional sem incluir e-mail, token ou credenciais. Isso reduz exposição por mensagens; não é uma promessa de impedir toda análise de tempo ou enumeração, e o cadastro ainda valida unicidade.

O domínio dos links vem de APP_URL, não do cabeçalho Host da solicitação. Após a troca, o usuário precisa entrar novamente; remember_token é renovado e as sessões daquele usuário no driver database são removidas. AuthenticateSession verifica o hash da senha nas requisições seguintes. Drivers de sessão alternativos precisam de avaliação própria para revogação de sessões legadas que ainda não tenham o hash registrado.

## Limites de requisição

- Login: bloqueio após 5 falhas por combinação de e-mail/IP, com espera de 60 segundos; sucesso limpa o contador. A rota também limita o volume geral a 30 requisições por minuto/IP.
- Cadastro: 3 requisições por minuto/IP.
- Solicitação de recuperação: 5 requisições por minuto/IP, além do intervalo de 60 segundos por conta aplicado pelo broker.
- Envio da nova senha: 10 requisições por minuto/IP.

Em múltiplas instâncias, os contadores precisam de cache compartilhado. Configure proxies confiáveis no servidor antes de depender do IP original do cliente; não aceite cabeçalhos de encaminhamento de qualquer origem.

## Sessões e resposta HTTP

Sessões são regeneradas no login/cadastro e invalidadas no logout. Formulários permanecem protegidos por CSRF. Conteúdo publicado continua escapado pelo Blade e Policies continuam protegendo edição/exclusão por autoria.

As páginas recebem nosniff, bloqueio de enquadramento, no-referrer, no-store e restrição de permissões de câmera/microfone/localização. Com APP_DEBUG=false, uma CSP limita recursos à própria origem e bloqueia JavaScript inline; a confirmação de exclusão foi movida para o arquivo JavaScript compilado. A CSP estrita pressupõe assets de produção, não o hot reload do Vite.

Cookies são HttpOnly e SameSite=Lax por padrão; Secure passa a ser o padrão quando APP_ENV=production. É possível sobrescrever configurações por ambiente, portanto elas devem ser conferidas na implantação.

## Configurar o envio de e-mail

O padrão MAIL_MAILER=log serve apenas para desenvolvimento: registra a mensagem em storage/logs/laravel.log e **não envia e-mail**. O log contém links válidos de recuperação; mantenha-o privado e nunca o publique ou use esse transporte em produção. Os testes usam Notification::fake e não enviam mensagens a terceiros.

Para entrega real, configure um provedor SMTP e um remetente verificado. Exemplo meramente ilustrativo, usando SMTPS quando suportado pelo provedor:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://app.example.com
MAIL_MAILER=smtp
MAIL_SCHEME=smtps
MAIL_HOST=smtp.example.com
MAIL_PORT=465
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS=contato@example.com
MAIL_FROM_NAME="Posts & Comments"
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
```

Substitua domínio, servidor e parâmetros pelos valores do provedor; não coloque senhas no repositório. Configure HTTPS, mantenha APP_KEY estável e protegida e execute as migrações. Após atualizar o ambiente, reconstrua o cache de configuração conforme seu procedimento de implantação. Não altere a senha de contas reais para testar: use uma conta de teste sob seu controle e confira entrega, validade, uso único e novo login.

## Evidências e limites

39 testes e 171 asserções aprovados na suíte Laravel, cobrindo recuperação, hash do token, expiração, uso único, isolamento entre contas, validação de senha, sessões, limites de tentativas, normalização de e-mail e autorização. No servidor local, POST sem token CSRF retornou HTTP 419; cookies de sessão apresentaram HttpOnly e SameSite=Lax. O link e a confirmação da recuperação foram conferidos no Chrome; o formulário se reorganizou em 390 pixels sem rolagem horizontal.

Entrega SMTP real, configuração HTTPS/proxy e operação de produção não foram validadas. Não há MFA, verificação obrigatória de e-mail, monitoramento de abuso distribuído ou auditoria externa de segurança. Essas proteções reduzem riscos; não constituem garantia absoluta de segurança.

Referências: [Laravel Password Broker](https://laravel.com/framework/docs/13.x/passwords) e [sessões autenticadas](https://laravel.com/framework/docs/13.x/authentication).

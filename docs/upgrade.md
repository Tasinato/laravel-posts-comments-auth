# Atualização de versões

Esta revisão migra o projeto de Laravel 12/PHP 8.2 para Laravel 13/PHP 8.5.12+. O ambiente frontend passa a usar Node.js 26 LTS, Vite 8 e laravel-vite-plugin 3; os testes usam PHPUnit 13. As dependências diretas foram consultadas nos registros oficiais e atualizadas para as versões estáveis compatíveis, com novas resoluções em composer.lock e package-lock.json.

## Ambiente local no Windows

Foi preparado um PHP 8.5.12 NTS x64 isolado em `.tools/php-8.5.12`, sem substituir a instalação global em C:/php. O pacote oficial Windows 8.5.12-1 teve SHA-256 conferido: `2a36a2edcb7a5b9252f638fd5e4cf84d7f6e066092654220dc0b63e5cf4e9723`. O Node.js 26.13.0 LTS também foi preparado de forma isolada em `.tools/node-v26.13.0-win-x64`, com SHA-256 conferido pelo SHASUMS256.txt oficial. Binários e configuração local não são versionados.

Para reproduzir em outro computador, instale PHP 8.5.12+ x64 NTS para CLI/FastCGI, suas extensões, Composer e Node.js 26 LTS. Não use builds candidatos a lançamento. No PowerShell desta cópia local, é possível selecionar o runtime preparado apenas para o terminal atual:

```powershell
$env:PATH = (Join-Path (Get-Location) '.tools/php-8.5.12') + ';' + (Join-Path (Get-Location) '.tools/node-v26.13.0-win-x64') + ';' + $env:PATH
php --version
composer install
npm.cmd ci
npm.cmd run build
php artisan test
php artisan serve --host=127.0.0.1 --port=8020
```

## Compatibilidade e dados

O mínimo declarado agora é PHP 8.5.12. A CI usa a versão estável mais recente da linha 8.5 e Node.js 26 LTS. As sessões usam JSON e o cache não aceita desserialização de objetos arbitrários. A mudança do formato de sessões pode encerrar sessões antigas e exigir novo login. Não foi executada limpeza ou recriação do banco de demonstração.

A aplicação não utiliza os recursos de MySQL, contratos personalizados ou helpers legados afetados pelas outras mudanças descritas no guia de atualização. A proteção CSRF segue o middleware padrão do Laravel 13. A recuperação de senha usa o broker nativo e a notificação personalizada em português.

## Validação

A suíte de testes, compilação de assets, compilação Blade e auditorias de Composer/npm são executadas novamente após a atualização. Os resultados efetivos da CI ficam na aba Actions do Pull Request. Ausência de alertas conhecidos não garante ausência de vulnerabilidades.

Fontes: [guia de atualização Laravel 13](https://laravel.com/framework/docs/13.x/upgrade), [PHP Downloads](https://www.php.net/downloads.php) e pacote oficial Windows fornecido em downloads.php.net.

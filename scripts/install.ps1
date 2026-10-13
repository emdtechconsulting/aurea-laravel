$ErrorActionPreference = 'Stop'
Set-Location (Split-Path $PSScriptRoot -Parent)
if (-not (Get-Command php -ErrorAction SilentlyContinue)) { throw 'Instala PHP 8.3 o superior (Laravel Herd) y abre una terminal nueva.' }
if (-not (Get-Command composer -ErrorAction SilentlyContinue)) { throw 'Instala Composer y abre una terminal nueva.' }
function Run-Step([scriptblock]$Command) { & $Command; if ($LASTEXITCODE -ne 0) { throw 'Falló un comando. Revisa el mensaje anterior.' } }
Run-Step { composer install --no-interaction }
if (-not (Test-Path '.env')) { Copy-Item '.env.example' '.env' }
if (-not (Test-Path 'database/database.sqlite')) { New-Item 'database/database.sqlite' -ItemType File | Out-Null }
$envContent = Get-Content '.env' -Raw
if ($envContent -notmatch '(?m)^APP_KEY=base64:') { Run-Step { php artisan key:generate } }
Run-Step { php artisan migrate }
Run-Step { php artisan db:seed }
Write-Host 'Ahora ejecuta: php artisan aurea:admin'
Write-Host 'Después: php artisan serve --host=127.0.0.1 --port=8000'

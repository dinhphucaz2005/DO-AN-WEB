Set-StrictMode -Version Latest
$ErrorActionPreference = "Stop"

Write-Host "=== Laravel SQLite setup (Windows / PowerShell) ==="

function Abort($msg) {
    Write-Error $msg
    exit 1
}

function Check-Cmd($cmd) {
    if (-not (Get-Command $cmd -ErrorAction SilentlyContinue)) { return $false }
    return $true
}

# Kiểm tra PHP và Composer
$Missing = @()
foreach ($cmd in "php","composer") {
    if (-not (Check-Cmd $cmd)) { $Missing += $cmd }
}

if ($Missing.Count -gt 0) {
    Write-Host "Missing: $($Missing -join ', ')"
    Write-Host "Please install missing packages manually (PHP, Composer)."
    Abort "Cannot continue without required commands."
}

$RootDir = (Get-Location).Path
Write-Host "Project root: $RootDir"

if (-not (Test-Path ".env")) {
    if (Test-Path ".env.example") {
        Copy-Item ".env.example" ".env"
        Write-Host "Created .env from .env.example"
    } else {
        Abort ".env.example not found. Create a .env file first."
    }
} else {
    Write-Host ".env already exists — will update relevant DB entries"
}

$DatabaseDir = Join-Path $RootDir "database"
if (-not (Test-Path $DatabaseDir)) { New-Item -ItemType Directory -Path $DatabaseDir | Out-Null }

$DbFile = Join-Path $DatabaseDir "database.sqlite"
if (-not (Test-Path $DbFile)) {
    New-Item -ItemType File -Path $DbFile | Out-Null
    Write-Host "Created SQLite file: $DbFile"
} else {
    Write-Host "SQLite file already exists: $DbFile"
}

# Cập nhật .env
function Set-Env($key, $value) {
    $content = Get-Content .env
    if ($content -match "^$key=") {
        (Get-Content .env) -replace "^$key=.*", "$key=$value" | Set-Content .env
    } else {
        Add-Content .env "$key=$value"
    }
}

Set-Env "DB_CONNECTION" "sqlite"
Set-Env "DB_DATABASE" "$DbFile"
Set-Env "DB_USERNAME" ""
Set-Env "DB_PASSWORD" ""

Write-Host ".env updated: DB_CONNECTION=sqlite, DB_DATABASE=$DbFile"

Write-Host "Running: composer install"
composer install --no-interaction --prefer-dist
if ($LASTEXITCODE -ne 0) { Abort "composer install failed" }

Write-Host "Running: php artisan key:generate"
php artisan key:generate --force --no-interaction
if ($LASTEXITCODE -ne 0) { Abort "php artisan key:generate failed" }

Write-Host "Running: php artisan migrate --force"
php artisan migrate --force --no-interaction
if ($LASTEXITCODE -ne 0) { Abort "php artisan migrate failed" }

Write-Host "`nSetup finished successfully."
Write-Host "You can start the dev server with: php artisan serve"

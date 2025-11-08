#!/usr/bin/env bash
set -euo pipefail

echo "=== Laravel SQLite setup (Linux - dnf) ==="

abort() {
  echo "ERROR: $1" >&2
  exit 1
}

check_cmd() {
  command -v "$1" >/dev/null 2>&1 || return 1
}

MISSING=()
for cmd in php composer; do
  if ! check_cmd "$cmd"; then
    MISSING+=("$cmd")
  fi
done

if [ ${#MISSING[@]} -ne 0 ]; then
  echo "Missing: ${MISSING[*]}"
  echo "Will attempt to install required packages via sudo dnf. You may be prompted for your password."
  sudo dnf install -y php php-cli php-mbstring php-xml php-intl php-sqlite3 composer || abort "Failed to install packages via dnf. Please install PHP and Composer manually."
fi

ROOT_DIR=$(pwd)
echo "Project root: $ROOT_DIR"

if [ ! -f .env ]; then
  if [ -f .env.example ]; then
    cp .env.example .env
    echo "Created .env from .env.example"
  else
    abort ".env.example not found. Create a .env file first or provide .env.example."
  fi
else
  echo ".env already exists — will update relevant DB entries"
fi

mkdir -p database
DB_FILE="${ROOT_DIR}/database/database.sqlite"
if [ ! -f "$DB_FILE" ]; then
  touch "$DB_FILE"
  echo "Created SQLite file: $DB_FILE"
else
  echo "SQLite file already exists: $DB_FILE"
fi

set_env() {
  local key="$1"
  local val="$2"
  if grep -qE "^${key}=" .env; then
    sed -i -E "s|^${key}=.*|${key}=${val}|" .env
  else
    echo "${key}=${val}" >> .env
  fi
}

set_env "DB_CONNECTION" "sqlite"
set_env "DB_DATABASE" "$DB_FILE"
set_env "DB_USERNAME" ""
set_env "DB_PASSWORD" ""

echo ".env updated: DB_CONNECTION=sqlite, DB_DATABASE=$DB_FILE"

echo "Running: composer install"
composer install --no-interaction --prefer-dist || abort "composer install failed"

echo "Running: php artisan key:generate"
php artisan key:generate --force --no-interaction || abort "php artisan key:generate failed"

echo "Running: php artisan migrate --force"
php artisan migrate --force --no-interaction || abort "php artisan migrate failed"

echo "\nSetup finished successfully."
echo "You can start the dev server with: php artisan serve"
echo "If run.sh is not executable, make it so: chmod +x run.sh"

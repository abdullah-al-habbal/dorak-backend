#!/bin/bash
set -Eeuo pipefail

TARGET="$1"
ENVIRONMENT="$2"
PHP_BIN="${3:-php}"

echo "🎯 Deploying to: $TARGET ($ENVIRONMENT)"

# ── Atomic swap ──────────────────────────────────────────────────────────
RELEASE_DIR="${TARGET}_release_$(date +%Y%m%d_%H%M%S)"
CURRENT_DIR="${TARGET}"

echo "📥 Cloning fresh repository..."
git clone git@github-dbnkalhbalbgmail:abdullah-al-habbal/dorak-backend.git "$RELEASE_DIR"
cd "$RELEASE_DIR"
git checkout ${{ github.ref_name }}

echo "📦 Installing dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "🗄️ Preparing directories..."
mkdir -p storage/logs storage/framework/{cache,sessions,views} storage/app/public

# Copy .env
if [ -f "$CURRENT_DIR/.env" ]; then
    cp "$CURRENT_DIR/.env" .env
else
    cp .env.example .env
    $PHP_BIN artisan key:generate
fi

# Symlink storage
rm -f public/storage
ln -s ../storage/app/public public/storage

echo "🗄️ Running migrations..."
MIGRATE_LOCK="/tmp/laravel_migrate_$(basename "$TARGET").lock"
flock -n "$MIGRATE_LOCK" $PHP_BIN artisan migrate --force

# ── Seed onboarding if flag present ─────────────────────────────────────
if [ "${SEED_ONBOARDING:-false}" = "true" ]; then
    echo "🌱 Seeding onboarding images..."
    $PHP_BIN artisan db:seed --class=OnboardingConfigSeeder --force
fi

echo "🧹 Cache..."
$PHP_BIN artisan optimize:clear
$PHP_BIN artisan config:cache
$PHP_BIN artisan route:cache
$PHP_BIN artisan view:cache

echo "🔄 Atomic swap..."
rm -rf "${CURRENT_DIR}_old" 2>/dev/null || true
if [ -d "$CURRENT_DIR" ]; then mv "$CURRENT_DIR" "${CURRENT_DIR}_old"; fi
mv "$RELEASE_DIR" "$CURRENT_DIR"

cd "$CURRENT_DIR"
$PHP_BIN artisan about

echo "✅ Deployment complete!"

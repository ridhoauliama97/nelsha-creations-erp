#!/bin/sh
set -e

cd /var/www/html

log() { echo "[entrypoint] $(date '+%Y-%m-%d %H:%M:%S') $*"; }

# ---------------------------------------------------------------------------
# 1. Sinkronkan environment Compose -> .env (agar artisan & php-fpm konsisten)
# ---------------------------------------------------------------------------
if [ ! -f .env ]; then
    cp .env.example .env
    log ".env tidak ada, disalin dari .env.example"
fi

sed_escape() { printf '%s' "$1" | sed -e 's/[\\&|]/\\&/g'; }

# Tulis nilai ke .env dengan quoting aman untuk dotenv (spasi/simbol khusus).
dotenv_quote() {
    case "$1" in
        ''|*[!A-Za-z0-9_./:@%+=,~-]*)
            printf '"%s"' "$(printf '%s' "$1" | sed -e 's/\\/\\\\/g' -e 's/"/\\"/g' -e 's/\$/\\\$/g')"
            ;;
        *) printf '%s' "$1";;
    esac
}

sync_env() {
    key="$1"; val="$2"
    [ -z "$val" ] && return 0
    quoted=$(dotenv_quote "$val")
    escaped=$(sed_escape "$quoted")
    if grep -q "^${key}=" .env; then
        sed -i "s|^${key}=.*|${key}=${escaped}|" .env
    else
        echo "${key}=${escaped}" >> .env
    fi
}

for key in APP_NAME APP_ENV APP_DEBUG APP_URL APP_KEY APP_LOCALE APP_FALLBACK_LOCALE \
           APP_FAKER_LOCALE APP_CURRENCY APP_TIMEZONE \
           DB_CONNECTION DB_HOST DB_PORT DB_DATABASE DB_USERNAME DB_PASSWORD; do
    eval "val=\${$key:-}"
    sync_env "$key" "$val"
done

if [ -z "${APP_KEY:-}" ]; then
    APP_KEY_IN_FILE=$(grep '^APP_KEY=' .env | cut -d= -f2-)
    if [ -z "$APP_KEY_IN_FILE" ]; then
        log "APP_KEY kosong — membuat kunci baru..."
        php artisan key:generate --no-interaction
    fi
fi

# ---------------------------------------------------------------------------
# 2. Tunggu MySQL siap (maks. 60 detik)
# ---------------------------------------------------------------------------
DB_HOST="${DB_HOST:-mysql}"
DB_PORT="${DB_PORT:-3306}"
DB_DATABASE="${DB_DATABASE:-aureuserp}"
DB_USERNAME="${DB_USERNAME:-aureuserp}"
DB_PASSWORD="${DB_PASSWORD:-secret}"
DB_CONNECTION="${DB_CONNECTION:-mysql}"

if [ "$DB_CONNECTION" = "mysql" ]; then
    log "Menunggu MySQL di ${DB_HOST}:${DB_PORT}..."
    i=0
    until php -r "try { new PDO('mysql:host=${DB_HOST};port=${DB_PORT}', '${DB_USERNAME}', '${DB_PASSWORD}'); } catch (Throwable \$e) { exit(1); }" 2>/dev/null; do
        i=$((i + 1))
        if [ "$i" -ge 60 ]; then
            log "ERROR: MySQL tidak bisa dihubungi setelah 60 detik."
            exit 1
        fi
        sleep 1
    done
    log "MySQL siap."
fi

# ---------------------------------------------------------------------------
# 3. Izin & symlink storage
# ---------------------------------------------------------------------------
mkdir -p storage/framework/cache/data storage/framework/sessions \
         storage/framework/views storage/logs storage/app/public \
         storage/fonts bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
chmod -R 775 storage bootstrap/cache 2>/dev/null || true
php artisan storage:link --no-interaction 2>/dev/null || true

# ---------------------------------------------------------------------------
# 4. Instalasi pertama: database kosong -> jalankan erp:install otomatis
#    (migrasi + seeder + role/permission + admin, mata uang IDR, negara ID)
#    Set AUTO_INSTALL=false untuk menonaktifkan (mis. pada container queue).
# ---------------------------------------------------------------------------
if [ "$DB_CONNECTION" = "mysql" ] && [ "${AUTO_INSTALL:-true}" = "true" ] && [ ! -f storage/installed ]; then
    TABLE_COUNT=$(php -r "
        try {
            \$pdo = new PDO('mysql:host=${DB_HOST};port=${DB_PORT}', '${DB_USERNAME}', '${DB_PASSWORD}');
            \$stmt = \$pdo->query(\"SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE()\");
            echo \$stmt->fetchColumn();
        } catch (Throwable \$e) {
            echo '0';
        }
    " 2>/dev/null || echo "0")

    if [ "$TABLE_COUNT" = "0" ]; then
        log "Database kosong — menjalankan instalasi ERP (currency=${APP_CURRENCY:-IDR}, country=ID)..."
        php artisan erp:install --force --no-interaction \
            --admin-name="${ADMIN_NAME:-Administrator}" \
            --admin-email="${ADMIN_EMAIL:-admin@example.com}" \
            --admin-password="${ADMIN_PASSWORD:-password}" \
            --country="${APP_COUNTRY:-ID}" \
            --currency="${APP_CURRENCY:-IDR}"
        log "Instalasi selesai. Admin: ${ADMIN_EMAIL:-admin@example.com}"
    else
        log "Menjalankan migrasi yang tertunda..."
        php artisan migrate --force --no-interaction || true
    fi
fi

log "Membersihkan cache konfigurasi..."
php artisan optimize:clear --no-interaction 2>/dev/null || true

# ---------------------------------------------------------------------------
# 5. Mode proses
# ---------------------------------------------------------------------------
if [ "${RUN_QUEUE:-0}" = "1" ]; then
    log "Menjalankan queue worker..."
    exec php artisan queue:work database --tries=3 --timeout=90 --sleep=3 --memory=512
fi

if [ "${RUN_SCHEDULER:-0}" = "1" ]; then
    log "Menjalankan scheduler..."
    exec php artisan schedule:work
fi

# php-fpm (daemon) lalu nginx sebagai proses utama (PID 1).
log "Menyalakan php-fpm & nginx..."
php-fpm -D
exec "$@"

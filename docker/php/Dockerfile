# syntax=docker/dockerfile:1
#
# AureusERP — image Alpine ringan (php-fpm-alpine + nginx-alpine).
# Build:
#   docker build -t aureuserp-app -f docker/php/Dockerfile .
# atau lewat Compose:
#   docker compose up --build -d

FROM php:8.4-fpm-alpine

ARG INSTALL_DEV=false
ARG COMPOSER_VERSION=2

ENV COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_MEMORY_LIMIT=-1 \
    TZ=Asia/Jakarta

# ---------------------------------------------------------------------------
# 1. Build dependencies & ekstensi PHP
# ---------------------------------------------------------------------------
RUN apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        freetype-dev \
        gmp-dev \
        imagemagick-dev \
        icu-dev \
        libjpeg-turbo-dev \
        libpng-dev \
        libxml2-dev \
        libzip-dev \
        linux-headers \
        mariadb-dev \
        oniguruma-dev \
        g++ \
        git \
        make \
        python3 \
        unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        calendar \
        exif \
        gd \
        gmp \
        intl \
        mbstring \
        mysqli \
        pcntl \
        pdo_mysql \
        soap \
        sockets \
        zip \
    && pecl install imagick \
    && docker-php-ext-enable imagick \
    && apk add --no-cache nodejs npm

# Runtime libs (tanpa -dev) agar ekstensi berjalan setelah build-deps dihapus.
# icu-data-full: data locale ICU lengkap — tanpa ini intl tidak mengenali locale
# 'id' (format mata uang/tanggal Indonesia berubah jadi fallback Inggris).
RUN apk add --no-cache \
        freetype \
        gmp \
        icu-libs \
        icu-data-full \
        imagemagick \
        libjpeg-turbo \
        libpng \
        libxml2 \
        libzip \
        mariadb-connector-c \
        oniguruma \
        tzdata \
    && rm -rf /usr/src/php

# Nginx Alpine (web server dalam image yang sama — tanpa volume berbagi).
RUN apk add --no-cache nginx \
    && rm -f /etc/nginx/http.d/default.conf \
    && mkdir -p /run /var/log/nginx

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
COPY docker/php/php.ini /usr/local/etc/php/conf.d/99-app.ini
# Alpine nginx memuat blok server dari http.d/*.conf (dalam http {}), bukan conf.d/.
COPY docker/nginx/default.conf /etc/nginx/http.d/app.conf

WORKDIR /var/www/html

# ---------------------------------------------------------------------------
# 2. Salin kode sumber (vendor/ & node_modules/ dikecualikan via .dockerignore)
# ---------------------------------------------------------------------------
COPY . .

# ---------------------------------------------------------------------------
# 3. Install: composer, frontend assets, Filament assets
# ---------------------------------------------------------------------------
RUN cp .env.example .env \
    && if [ "$INSTALL_DEV" = "true" ]; then \
         composer install --no-interaction --prefer-dist; \
       else \
         composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist; \
       fi \
    && php artisan key:generate --no-interaction \
    && npm ci --no-audit --no-fund \
    && npm run build \
    && php artisan package:discover --ansi \
    && php artisan filament:assets --no-interaction \
    && php artisan vendor:publish --tag=laravel-assets --force --ansi \
    && rm -rf node_modules /root/.npm /root/.composer \
    && apk del .build-deps \
    && rm -rf /tmp/* /var/tmp/*

# Izin direktori yang harus ditulis aplikasi.
RUN mkdir -p \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        storage/app/public \
        bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

HEALTHCHECK --interval=15s --timeout=5s --start-period=40s --retries=10 \
    CMD wget -qO- http://127.0.0.1/health >/dev/null || exit 1

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["nginx", "-g", "daemon off;"]

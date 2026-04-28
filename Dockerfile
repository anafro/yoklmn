FROM dunglas/frankenphp:1-php8.5.2
RUN install-php-extensions \
    pcntl \
    pdo_pgsql \
    pgsql \
    pdo_sqlite \
    sqlite3 \
    pdo_mysql \
    zip \
    opentelemetry \
    redis-6.3.0

WORKDIR /app
COPY --from=composer:2.9.5 /usr/bin/composer /usr/bin/composer
RUN apt-get update \
    && apt-get install -y --no-install-recommends unzip git nodejs npm \
    && rm -rf /var/lib/apt/lists/*
RUN npm install -g bun@1.3.12
COPY ./package.json ./package-lock.json ./
COPY ./packages/biosphere-client/ ./packages/biosphere-client/
COPY ./packages/laravel-biosphere-server/ ./packages/laravel-biosphere-server/
RUN npm ci
COPY ./packages/laravel-biosphere/ ./packages/laravel-biosphere/
COPY ./composer.json ./
RUN OTEL_SDK_DISABLED=false composer install --no-dev --optimize-autoloader --no-scripts
COPY . .
RUN composer run-script post-autoload-dump
RUN npm run build
RUN ["chmod", "+x", "./entrypoint.sh"]

EXPOSE 80
ENTRYPOINT ["./entrypoint.sh"]
CMD ["php", "artisan", "octane:frankenphp", "--host=0.0.0.0", "--port=80"]

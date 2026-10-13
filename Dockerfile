FROM php:8.3-cli
RUN apt-get update && apt-get install -y --no-install-recommends git unzip libzip-dev libonig-dev libxml2-dev libsqlite3-dev && docker-php-ext-install pdo_sqlite mbstring zip dom && rm -rf /var/lib/apt/lists/*
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /app
CMD ["php","artisan","serve","--host=0.0.0.0","--port=8000"]

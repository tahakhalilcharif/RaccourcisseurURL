# FROM php:8.2-fpm

# RUN apt-get update && apt-get install -y \
#     git \
#     curl \
#     libpng-dev \
#     libonig-dev \
#     libxml2-dev \
#     libsqlite3-dev \
#     zip \
#     unzip \
#     nodejs \
#     npm \
#     && apt-get clean \
#     && rm -rf /var/lib/apt/lists/*

# RUN docker-php-ext-install pdo_sqlite pdo_mysql mbstring exif pcntl bcmath gd

# COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# WORKDIR /var/www

# COPY . .

# RUN composer install --optimize-autoloader --no-dev

# RUN npm ci && npm run build

# RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
# RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# RUN mkdir -p /var/www/database \
#     && touch /var/www/database/database.sqlite \
#     && chown www-data:www-data /var/www/database/database.sqlite

# EXPOSE 9000

# CMD ["php-fpm"]

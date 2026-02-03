FROM php:8.3-fpm

# ===============================
# System dependencies
# ===============================
RUN apt-get update && apt-get install -y \
    nginx \
    supervisor \
    git \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libpq-dev \
    zip \
    curl \
    nodejs \
    npm \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install pdo pdo_mysql pdo_pgsql gd zip opcache

# ===============================
# Composer
# ===============================
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# ===============================
# Copy source
# ===============================
COPY . .

# ===============================
# PHP deps
# ===============================
RUN composer install --no-dev --optimize-autoloader --no-interaction

# ===============================
# Frontend build
# ===============================
RUN npm install && npm run build

# ===============================
# Permissions
# ===============================
RUN chown -R www-data:www-data /var/www \
 && chmod -R 775 storage bootstrap/cache

# ===============================
# Nginx + Supervisor
# ===============================
COPY nginx.conf /etc/nginx/nginx.conf
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 8080

CMD ["/usr/bin/supervisord"]

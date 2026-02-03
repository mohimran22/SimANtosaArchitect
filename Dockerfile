FROM php:8.3-fpm

# ===============================
# Install system dependencies
# ===============================
RUN apt-get update && apt-get install -y \
    nginx \
    git \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libpq-dev \
    zip \
    curl \
    supervisor \
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql gd zip opcache

# ===============================
# Install Composer
# ===============================
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ===============================
# Set working directory
# ===============================
WORKDIR /var/www

# ===============================
# Copy project files
# ===============================
COPY . .

# ===============================
# Install PHP dependencies
# ===============================
RUN composer install --no-dev --optimize-autoloader

# ===============================
# Build frontend (Vite)
# ===============================
RUN npm install && npm run build

# ===============================
# Laravel Optimization
# ===============================
RUN php artisan key:generate || true \
 && php artisan storage:link || true \
 && php artisan config:clear \
 && php artisan config:cache \
 && php artisan view:cache

# ===============================
# Permissions
# ===============================
RUN chown -R www-data:www-data /var/www \
 && chmod -R 775 storage bootstrap/cache

# ===============================
# Nginx & Supervisor config
# ===============================
COPY nginx.conf /etc/nginx/nginx.conf
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# ===============================
# Expose Railway Port
# ===============================
EXPOSE 8080

# ===============================
# Start services
# ===============================
CMD ["/usr/bin/supervisord"]

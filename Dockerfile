# ===============================
# STAGE 1 — Node build (frontend)
# ===============================
FROM node:20 AS nodebuilder

WORKDIR /app

COPY package*.json ./
RUN npm install

COPY resources resources
COPY vite.config.js .
COPY tailwind.config.js* ./
COPY postcss.config.js* ./
RUN npm run build


# ===============================
# STAGE 2 — PHP runtime
# ===============================
FROM php:8.3-fpm

# ---------- system deps ----------
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
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install pdo pdo_mysql pdo_pgsql gd zip opcache

# ---------- composer ----------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# ---------- copy app ----------
COPY . .

# ---------- copy built assets from node ----------
COPY --from=nodebuilder /app/public/build /var/www/public/build

# ---------- php deps ----------
RUN composer install --no-dev --optimize-autoloader --no-interaction

# ---------- permissions ----------
RUN chown -R www-data:www-data /var/www \
 && chmod -R 775 storage bootstrap/cache

# ---------- nginx + supervisor ----------
COPY nginx.conf /etc/nginx/nginx.conf
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 8080

COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
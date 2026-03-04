FROM php:8.3-fpm

# System deps
RUN apt-get update && apt-get install -y \
  git unzip libpq-dev libzip-dev zip curl nginx \
  && docker-php-ext-install pdo pdo_pgsql zip \
  && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy app
COPY . .

# Install PHP deps
RUN composer install --no-dev --optimize-autoloader --no-interaction

# (Optional) build frontend assets if you use Vite
# Install Node (simple approach) + build
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
  && apt-get update && apt-get install -y nodejs \
  && npm ci && npm run build \
  && apt-get purge -y nodejs \
  && apt-get autoremove -y \
  && rm -rf /var/lib/apt/lists/*

# Nginx config
COPY ./render/nginx.conf /etc/nginx/sites-available/default

# Permissions (Laravel storage)
RUN chown -R www-data:www-data storage bootstrap/cache

# Entrypoint
COPY ./render/start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 10000
CMD ["/start.sh"]

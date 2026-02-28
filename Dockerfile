FROM php:8.2-cli

# Dépendances système nécessaires à GD et PostgreSQL
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libzip-dev \
    libpq-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libsodium-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install zip pdo pdo_pgsql gd sodium

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copier le projet
COPY . .

# Installer dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Installer Node.js & npm (pour Vite)
RUN apt-get update && apt-get install -y nodejs npm

# Installer les packages frontend
RUN npm install


# Compiler les assets pour production
RUN npm run build


# Permissions Laravel
RUN chmod -R 775 storage bootstrap/cache

# Port Render
EXPOSE 10000

CMD php -S 0.0.0.0:10000 -t public

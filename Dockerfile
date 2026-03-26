# Image de base PHP avec Apache
FROM php:8.2-apache

# Installation des dépendances système (pour PostgreSQL et le découpage d'images)
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libpng-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_pgsql gd

# Activation du module de réécriture d'Apache (nécessaire pour Laravel)
RUN a2enmod rewrite

# Définition du dossier de travail
WORKDIR /var/www/html

# Copie du projet dans le conteneur
COPY . .

# Installation de Composer (l'outil de dépendances PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-interaction --optimize-autoloader

# Permissions pour Laravel (indispensable pour les logs et le cache)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Exposition du port 80
EXPOSE 80

# Image de base PHP avec Apache
FROM php:8.2-apache

#  Installation des dépendances système (on ajoute curl et ca-certificates)
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libpng-dev \
    zip \
    unzip \
    git \
    curl \
    ca-certificates \
    gnupg \
    && docker-php-ext-install pdo pdo_pgsql gd

#  Installation de Node.js et NPM (Méthode directe)
RUN apt-get update && apt-get install -y nodejs npm
# Vérification immédiate (si ça échoue ici, le build s'arrête)
RUN node -v && npm -v

#  Activation du module de réécriture d'Apache
RUN a2enmod rewrite

#  Dossier de travail
WORKDIR /var/www/html

#  Copie du projet
COPY . .

#  Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-interaction --optimize-autoloader

#  Compilation des assets (Vite) - C'est ici que l'erreur 403 et Vite se règlent !
RUN npm install && npm run build

#  Configuration Apache (Pointage sur /public)
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

#  Permissions (Crucial pour Laravel)
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Exposition du port 80
EXPOSE 80

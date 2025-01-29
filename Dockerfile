# Používáme oficiální PHP obraz s Apache
FROM php:8.1-apache

# Instalujeme závislosti (například pro PDO, MySQL a další)
RUN docker-php-ext-install pdo pdo_mysql

# Kopírujíme soubory aplikace
COPY . /var/www/html/

# Nastavujeme správné oprávnění pro soubory
RUN chown -R www-data:www-data /var/www/html

# Exponujeme port 80 pro přístup k API
EXPOSE 80

# Spustíme Apache
CMD ["apache2-foreground"]

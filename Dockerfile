FROM php:8.2-apache

# 1. Install necessary extensions (like ZIP for bulk actions)
RUN apt-get update && apt-get install -y \
    libzip-dev \
    && docker-php-ext-install zip

# 2. Copy all your project files to the web server root
COPY . /var/www/html/

# 3. Set Working Directory
WORKDIR /var/www/html/

# 4. FIX PERMISSIONS (Critical for File Uploads)
# Create folders if missing and give Apache (www-data) write access
RUN mkdir -p uploads data && \
    chown -R www-data:www-data /var/www/html/uploads && \
    chown -R www-data:www-data /var/www/html/data && \
    chmod -R 755 /var/www/html/uploads && \
    chmod -R 755 /var/www/html/data

# 5. Configure Port for Railway
# Railway provides a dynamic PORT env var. We update Apache to listen on it.
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# 6. Enable URL rewriting (good practice)
RUN a2enmod rewrite

# 7. Start Apache
CMD ["apache2-foreground"]

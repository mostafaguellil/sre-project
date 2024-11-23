# Stage 1: Build stage
FROM debian:latest AS build

# Set environment variables for non-interactive installs
ENV DEBIAN_FRONTEND=noninteractive

# Install Apache, PHP, MariaDB client (for PHP to connect to DB), phpMyAdmin, and OpenSSL
RUN apt-get update && apt-get install -y \
    apache2 \
    mariadb-client \
    php \
    libapache2-mod-php \
    php-mysql \
    phpmyadmin \
    openssl && \
    apt-get clean

# Copy the custom hosts file
COPY ./hosts /etc/hosts

# Copy your HTML and PHP files into the Apache web directory
COPY ./html /var/www/html/
COPY ./sql/init.sql /docker-entrypoint-initdb.d/init.sql

# Copy secrets
COPY ./secrets/mysql_root_password /run/secrets/mysql_root_password
# Ensure permissions for secrets
RUN chmod 666 /run/secrets/mysql_root_password

# Expose PHPMyAdmin
RUN ln -s /usr/share/phpmyadmin /var/www/html/phpmyadmin

# Generate a self-signed SSL certificate
RUN mkdir -p /etc/apache2/ssl && \
    openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -keyout /etc/apache2/ssl/apache-selfsigned.key \
    -out /etc/apache2/ssl/apache-selfsigned.crt \
    -subj "/C=US/ST=State/L=City/O=Organization/OU=OrgUnit/CN=localhost"

# Enable SSL and configure Apache for HTTPS
RUN a2enmod ssl && \
    a2ensite default-ssl && \
    echo "SSLCertificateFile /etc/apache2/ssl/apache-selfsigned.crt\nSSLCertificateKeyFile /etc/apache2/ssl/apache-selfsigned.key" >> /etc/apache2/sites-available/default-ssl.conf

EXPOSE 80 443

# Command to start Apache in the foreground
CMD ["apache2ctl", "-D", "FOREGROUND"]

# Stage 2: Final stage
FROM debian:latest

# Set environment variables for non-interactive installs
ENV DEBIAN_FRONTEND=noninteractive

# Copy everything from the build stage
COPY --from=build / /

# Expose the ports
EXPOSE 80 443

# Command to start Apache in the foreground
CMD ["apache2ctl", "-D", "FOREGROUND"]

FROM php:7.2-apache

ENV APP_ENV "prod"
ENV DATABASE_URL "mysql://db_user:db_password@127.0.0.1:3306/db_name"

RUN a2enmod rewrite
RUN apt-get update \
    && apt-get install -y curl sed zlib1g-dev \
    && docker-php-ext-install zip pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY apache/sites-enabled/000-default.conf /etc/apache2/sites-enabled/000-default.conf
COPY ./ /var/www/

RUN SYMFONY_ENV="$APP_ENV" composer install -d /var/www/
RUN chmod 0777 -R /var/www/var/
RUN chmod +x /var/www/entrypoint.sh

ENTRYPOINT ["/var/www/entrypoint.sh"]
CMD ["apache2-foreground"]
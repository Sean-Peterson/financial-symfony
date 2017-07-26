FROM php:7.0-apache

RUN apt-get update && apt-get install -y git-core unzip emacs-nox \
    && docker-php-ext-install mysqli opcache

RUN \
    apt-get update && \
    apt-get install libldap2-dev -y && \
    rm -rf /var/lib/apt/lists/* && \
    docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu/ && \
    docker-php-ext-install ldap

RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && curl -LsS https://symfony.com/installer -o /usr/local/bin/symfony \
    && chmod a+x /usr/local/bin/symfony

RUN curl -LsS https://phar.phpunit.de/phpunit.phar -o /usr/local/bin/phpunit \
    && chmod a+x /usr/local/bin/phpunit

ADD vhost.conf /etc/apache2/sites-available/000-default.conf
ADD symfony.ini /usr/local/etc/php/conf.d/

RUN a2enmod rewrite
RUN a2ensite 000-default.conf

COPY php /var/www/html

RUN composer install --no-scripts

RUN rm -Rf var/cache/dev/*

RUN usermod -u 1000 www-data
RUN chown -R www-data:www-data /var/www/html/var/cache
RUN chown -R www-data:www-data /var/www/html/var/logs

EXPOSE 8000
EXPOSE 80

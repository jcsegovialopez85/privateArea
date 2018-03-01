FROM mileschou/phalcon:5.6-apache

RUN apt-get update && \
    apt-get install -y --no-install-recommends git zip && \
	apt-get install -y vim

RUN curl -sS https://getcomposer.org/installer | \
    php -- --install-dir=/usr/bin/ --filename=composer

COPY . /var/www/html
COPY ./000-default.conf /etc/apache2/sites-enabled/000-default.conf

RUN a2enmod rewrite

RUN composer install --no-dev --no-interaction -o 




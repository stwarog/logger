FROM registry.gitlab.com/efficio1/engineering/docker/efficio-docker-image/efficio-php-74-apache:3.1 as BackendDependencies

WORKDIR /var/www/html

# Install project dependancies
COPY composer.json composer.lock /var/www/html/
RUN composer install -o --no-interaction --no-progress --no-suggest

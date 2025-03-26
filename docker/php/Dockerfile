FROM php:8.2-fpm

# Устанавливаем зависимости
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    zip
    #&& docker-php-ext-install intl opcach exif pcntl bcmath


# Устанавливаем Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

# Устанавливаем рабочую директорию
WORKDIR /var/www

# Копируем файлы проекта
COPY . .

# Устанавливаем зависимости через Composer
#RUN composer install --no-dev --no-interaction --prefer-dist

# Настраиваем права доступа
RUN chown -R www-data:www-data /var/www

CMD ["php-fpm"]
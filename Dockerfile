FROM php:8.4-fpm-alpine

# Установка системных зависимостей
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    oniguruma-dev \
    libxml2-dev \
    zip \
    unzip \
    postgresql-dev \
    linux-headers

# Установка PHP расширений
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd

# Получение последней версии Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Установка рабочей директории
WORKDIR /var/www

# Установка прав доступа
RUN addgroup -g 1000 www
RUN adduser -u 1000 -G www -h /home/www -s /bin/sh -D www

# Копирование существующих файлов проекта
COPY --chown=www:www . /var/www

# Change current user to www
USER www

# Открытие порта
EXPOSE 9000

CMD ["php-fpm"] 
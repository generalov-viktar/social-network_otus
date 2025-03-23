# Социальная сеть

Проект социальной сети на Laravel с использованием Docker.

## Требования

- Docker
- Docker Compose

## Установка

1. Клонируйте репозиторий:
```bash
git clone https://github.com/generalov-viktar/social-network_otus.git
cd social-network_otus
```

2. Создайте файл .env из примера:
```bash
cp .env.example .env
```

3. Соберите и запустите Docker-контейнеры:
```bash
docker-compose up -d --build
```

4. Войдите в контейнер приложения:
```bash
docker-compose exec app sh
```

5. Установите зависимости:
```bash
composer install
```

6. Сгенерируйте ключ приложения:
```bash
php artisan key:generate
```

7. Выполните миграции:
```bash
php artisan migrate
```

## Запуск

Приложение будет доступно по адресу: http://localhost:8000

## API Endpoints

### Авторизация
- POST /api/login - Авторизация пользователя
- POST /api/register - Регистрация нового пользователя

### Пользователи
- GET /api/user/{id} - Получение информации о пользователе

## Postman Collection

В репозитории находится файл `social-network.postman_collection.json` с коллекцией запросов для тестирования API.
Все ответы обрабатываются автоматически и если импортировать файл Otus.postman_environment.json, то все переменные будут автоматические подставляться в запросах.

## Для запуска тестов выполните:
```bash
php artisan test
```
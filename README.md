# TRAINING LARAVEL

## Start App
Launch Docker Desktop.  

### In Terminal
Clone repository:
```php
cd YourDirectory
git clone https://github.com/linbhui/Training-Laravel.git
```

To start:
```php
docker compose up -d
```

To stop:
```php
docker compose down
```

## Set Up Database
In `.env` file, customize:
```php
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

### In Docker Desktop
Access `Exec` in laravel app container, 

Migrate base tables:
```php
php artisan migrate --path=database/migrations/base
```

Seed dummy data:
```php
php artisan db:seed
```

Migrate the additions:
```php
php artisan migrate --path=database/migrations/constraints
```

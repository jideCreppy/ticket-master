# Ticket Master

### Ticket Master is a fun Laravel(V.11) API back-end project demonstrating the use of modern API development techniques used in a variety of systems today such as:

1. API versioning
2. Token Authentication
3. Laravel Sail (local development containers)
4. Scribe API endpoint documentation
5. Larastan/PHPStan Code/Static analysis (code quality)
6. Pest - Architecture Testing (code quality)
7. Pest - Unit and Feature Testing (code quality)
8. Restful APIs

## Dependencies
1. PHP 8.2
2. Composer
3. NPM (Nope Package Manager)
4. Docker Desktop (make sure this is started and running)

## Within the project folder

```php
Run Composer install
```

### Make a copy of .env.example and rename it to .env

```php
Run php artisan key:generate
This should generate a new APP_KEY environment variable in your .env file
```

### Open a new terminal to create the database and start the server

```php
Run sail up -d
Run php artisan migrate --seed
Run php artisan serve
```

### For the full API documentation navigate to http://localhost:8000/docs

## To regenerate the scribe documentation
#### Send a POST request to http://localhost:8000/api/login with 'manager@example.com' as the username and 'password' as the password. Copy the token returned and add a new environment variable to the .env file called SCRIBE_AUTH_KEY={TOKEN}

```php
Run php artisan scribe:generate
```
## Testing

```php
On Mac/Linux Run touch database/database.sqlite to create your test database
On windows create the above file in the database directory
```

```php
Run php artisan test
```

## Larastan Code analysis
```php
Run ./vendor/bin/phpstan analyse --memory-limit=1G
```




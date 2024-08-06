# Ticket Master

### Ticket Master is a fun service desk ticketing API concept built with Laravel(V.11) to demonstrate the use of modern API development techniques adopted in modern systems today such as:

1. API versioning
2. Token Authentication
3. Token Abilities and Access Privileges
4. Authorization Policy (versioned)
5. Laravel Sail (local development containers - mysql and mailpit)
6. Scribe API endpoint documentation
7. Larastan/PHPStan Code/Static analysis (code quality)
8. Pest - Architecture Tests (code quality)
9. Pest - Feature Tests (code quality)
10. Restful APIs 
11. Restful API Responses (JSON API Specification)
12. Simple mail notifications using Mailpit 13Centralised error handling

## Dependencies
1. PHP 8.2
2. Composer
3. NPM (Node Package Manager)
4. Docker Desktop (make sure this is started and running)

## Within the project folder

```php
Run composer install
```

### Make a copy of .env.example and rename it to .env

```php
Run php artisan key:generate
This should generate a new APP_KEY environment variable in your .env file with your applications key
```
# Database

### Update the following .env variables
```php
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password
```
# Mail Notification (Mailpit)

```php
Add the following environment variables to your .env configuration file. Then in a new browser window navigate to: http:://localhost:8025/ 

MAIL_MAILER=smtp
MAIL_HOST=0.0.0.0
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="admin@ticketmaster.com"
MAIL_FROM_NAME="${APP_NAME}"

Note: 0.0.0.0 is the default docker network bridge IP. You may also be able to substitute this for 'mailpit' which is the network name in docker.
```

### Open a new terminal to create the database and start the database, mail and local web server

```php
Run sail up -d
Run php artisan migrate --seed
Run php artisan serve
```
# API Documentation
#### For the full API documentation navigate to http://localhost:8000/docs

#### To regenerate the scribe documentation send a POST request to http://localhost:8000/api/login with 'manager@example.com' as the username and 'password' as the password. 
#### The above user is an admin user with more privileges/abilities. Other users in the database that aren't admin users can only manage their own information using the API. Copy the token returned and add a new environment variable to the .env file called SCRIBE_AUTH_KEY={TOKEN}

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

## Larastan Code Analysis
```php
Run ./vendor/bin/phpstan analyse --memory-limit=1G
```




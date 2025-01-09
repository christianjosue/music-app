#!/bin/bash

# Install composer dependencies
composer install

# Run migrations and seeders
php artisan migrate:fresh --seed

# Run laravel server
exec php artisan serve --host=0.0.0.0 --port=8000
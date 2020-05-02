#!/bin/bash

cp .env.example .env
cp .env.testing.example .env.testing
php artisan key:generate
php artisan migrate --seed
chmod +x -R ./storage

php-fpm

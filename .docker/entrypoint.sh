#!/bin/bash

cp .env.example .env
php artisan key:generate
php artisan migrate --seed
chmod +x -R ./storage

php-fpm

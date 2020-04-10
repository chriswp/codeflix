#!/bin/bash

php artisan key:generate
php artisan migrate --seed
php-fpm

chmod +x -R ./storage

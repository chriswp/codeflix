#!/bin/bash

php artisan key:generate
php artisan migrate --seed -R
chmod +x -R ./storage

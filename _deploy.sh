#!/bin/sh
set -e

PHP=/usr/bin/php

git pull origin main

$PHP /usr/bin/composer install --no-interaction --optimize-autoloader --no-dev

$PHP artisan migrate --force

$PHP artisan optimize:clear

#!/usr/bin/env bash
git pull && composer update && php artisan migrate --force && npm i && npm run prod && php artisan clear-all

#!/bin/bash

cd /var/www/jdj

composer install --optimize-autoloader

php app/console app:install --no-interaction
npm rebuild node-sass
npm install
./node_modules/.bin/gulp
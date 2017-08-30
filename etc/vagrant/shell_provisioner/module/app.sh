#!/bin/bash

cd /var/www/jdj

composer install --optimize-autoloader

php bin/console app:install --no-interaction
yarn install
# TODO remove admin argument
yarn run gulp admin
#!/usr/bin/env bash

# mysql
sed -i 's/database_host.*/database_host: 127.0.0.1/' app/config/parameters.yml
sed -i "s/database_user.*/database_user: ${MYSQL_USER}/" app/config/parameters.yml
sed -i "s/database_password.*/database_password: ${MYSQL_PASSWORD}/" app/config/parameters.yml
sed -i "s/dbname.*/dbname: test/" app/config/config_test.yml

# elastic search
sed -i "s/fos_elastica\.host.*/fos_elastica\.host: 127.0.0.1/" app/config/parameters.yml
#!/bin/bash

# install gem
sudo gem update --system

# install sass
sudo gem install sass

# expose mysql
sudo sed -i -e "s/127\.0\.0\.1/0\.0\.0\.0/g" /etc/mysql/my.cnf
sudo service mysql restart
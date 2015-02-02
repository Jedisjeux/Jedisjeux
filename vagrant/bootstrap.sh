#!/bin/bash
#
set -x
sudo apt-get update
mkdir -p /etc/puppet/modules

puppet module install example42/puppi --version 2.1.9 --force
puppet module install example42/apache --version 2.1.7 --force
puppet module install puppetlabs/stdlib --version 4.1.0 --force
puppet module install puppetlabs/apt --version 1.4.2 --force
puppet module install example42/php --version 2.0.18 --force
puppet module install puppetlabs/mysql --version 2.2.3 --force
puppet module install willdurand/composer --version 0.0.6 --force
puppet module install maestrodev/wget --version 1.4.1 --force
puppet module install softek/java7 --version 0.1.0 --force
puppet module install /vagrant/puppet/modules/puppet-tomcat-2.1.5.tar.gz --force
puppet module install puppetlabs-rabbitmq --version 3.1.0 --force

# install elastic search
wget -qO - https://packages.elasticsearch.org/GPG-KEY-elasticsearch | sudo apt-key add -
sudo add-apt-repository "deb http://packages.elasticsearch.org/elasticsearch/1.4/debian stable main"
sudo apt-get update && sudo apt-get install elasticsearch
sudo update-rc.d elasticsearch defaults 95 10
sudo /etc/init.d/elasticsearch start

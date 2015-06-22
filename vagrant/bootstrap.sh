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
puppet module install puppetlabs-java --force
puppet module install /vagrant/puppet/modules/puppet-tomcat-2.1.5.tar.gz --force
puppet module install puppetlabs-rabbitmq --version 3.1.0 --force
puppet module install elasticsearch/elasticsearch  --force

# install sass
sudo gem install sass

# install compass
sudo gem install compass

# install locate
sudo apt-get install mlocate
sudo updatedb

# enable swap
sudo /bin/dd if=/dev/zero of=/var/swap.1 bs=1M count=1024
sudo /sbin/mkswap /var/swap.1
sudo /sbin/swapon /var/swap.1

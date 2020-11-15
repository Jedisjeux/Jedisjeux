#!/usr/bin/env bash

source "$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)/../../../bash/common.lib.sh"
source "$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)/../../../bash/application.sh"

print_header "Activating memcached extension" "Jedisjeux"
run_command "echo \"extension = memcached.so\" >> ~/.phpenv/versions/$(phpenv version-name)/etc/conf.d/travis.ini" || exit $?

print_header "Installing elasticsearch" "Jedisjeux"
run_command "curl -O https://artifacts.elastic.co/downloads/elasticsearch/elasticsearch-5.1.1.deb && sudo dpkg -i --force-confnew elasticsearch-5.1.1.deb && sudo service elasticsearch restart"
run_command "sleep 10"

# Download and configure Symfony webserver
print_header "Downloading Symfony CLI" "Sylius"
if [ ! -f $JEDISJEUX_CACHE_DIR/symfony ]; then
    run_command "wget https://get.symfony.com/cli/installer -O - | bash"
    run_command "mv ~/.symfony/bin/symfony $JEDISJEUX_CACHE_DIR"
fi
run_command "$JEDISJEUX_CACHE_DIR/symfony version"

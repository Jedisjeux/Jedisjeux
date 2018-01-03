#!/usr/bin/env bash

source "$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)/../../../bash/common.lib.sh"
source "$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)/../../../bash/application.sh"

print_header "Activating memcached extension" "Jedisjeux"
run_command "echo \"extension = memcached.so\" >> ~/.phpenv/versions/$(phpenv version-name)/etc/conf.d/travis.ini" || exit $?

print_header "Installing elasticsearch" "Jedisjeux"
run_command "curl -O https://download.elastic.co/elasticsearch/elasticsearch/elasticsearch-1.7.3.deb && sudo dpkg -i --force-confnew elasticsearch-1.7.3.deb && sudo service elasticsearch restart"
run_command "sleep 10"

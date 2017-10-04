#!/usr/bin/env bash

source "$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)/../../../bash/common.lib.sh"
source "$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)/../../../bash/application.sh"

print_header "Installing dependencies" "Jedisjeux"
run_command "composer install --no-interaction --prefer-dist" || exit $?

print_header "Warming up dependencies" "Jedisjeux"
run_command "yarn install" || exit $?

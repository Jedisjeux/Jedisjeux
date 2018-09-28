#!/usr/bin/env bash

source "$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)/../../../bash/common.lib.sh"

code=0
commands=(
    validate-composer
    validate-composer-security
    validate-behat-features
    validate-doctrine-schema
    validate-twig
    validate-yaml-files
    validate-yarn-packages
    test-phpspec
    test-phpstan
    test-phpunit
    test-fixtures
    test-behat-without-javascript
    update-behat-with-old-config
    test-behat-without-javascript
    test-behat-with-javascript
)

for command in ${commands[@]}; do
    "$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)/script/${command}" || code=$?
done

exit ${code}

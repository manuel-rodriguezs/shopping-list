#!/bin/sh
php bin/console doctrine:database:drop --force --env=test
php bin/console doctrine:database:create --env=test
php bin/console doctrine:migrations:migrate -q --env=test
php bin/console doctrine:fixtures:load --env=test --no-interaction
php bin/phpunit --coverage-html=var/coverage/
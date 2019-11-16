# the-shopping-list
Symfony 4 test

`docker-compose up -d`

```
docker exec the-shopping-list_php_1 \
    composer install \
    && chmod +x bin/* \
    && bin/console doctrine:database:create
```
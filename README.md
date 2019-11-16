# Shopping list
Symfony 4 test

`docker-compose up -d`

```
docker exec shopping-list_php_1 \
       composer install \
    && bin/console doctrine:database:create
```
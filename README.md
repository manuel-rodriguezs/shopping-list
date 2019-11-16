# Shopping list
Symfony 4 test

```
docker-compose up -d
```

```
docker exec shopping-list_php_1 composer install
```
```
docker exec shopping-list_php_1 php bin/console doctrine:database:create
```
```
docker exec shopping-list_php_1 php bin/console doctrine:migrations:migrate -q
```
# Shopping list
A Symfony 4 test app.

### Prerequisites

All you need to have installed on your system is **Docker**.

It is recommended to have **git** installed to clone the repository, but you could just download it.

Once you have the source code, here are the steps to deploy the application.

#### Step 1: Deploy de docker stack
```
docker-compose up -d
```

#### Step 2: Install Symfony dependencies
```
docker exec shoplist_php_1 composer install
```

#### Step 3: Create the database schema

```
docker exec shoplist_php_1 php bin/console doctrine:database:create

docker exec shoplist_php_1 php bin/console doctrine:migrations:migrate -q
```

#### Step 4 (Optional): Create the database schema for tests
```
docker exec shoplist_php_1 php bin/console doctrine:database:create --env=test

docker exec shoplist_php_1 php bin/console doctrine:migrations:migrate -q --env=test

docker exec shoplist_php_1 php bin/console doctrine:fixtures:load --env=test -q
```


**That's all.**

Now you can enter the app in this url:

http://localhost:8888

### What's about the REST API?

This app implemts six endpoints of a REST API. Let's see each one by one.

First of all, here're two considerations:

* All the comunication is in JSON, so, you must add the `Content-Type: application/json` header the requests that need Param Object.
* The endpoints are behind the url `http://localhost:8888/api`

### [POST] /supermarket

##### Param object

name=[string|required]

##### Sample Request JSON

```json
{
  "name": "Carrefour"
}
```

#### Success Response Sample

HTTP Response Code: 200

```json
{
  "id": 3,
  "name": "Carrefour",
  "prices": [],
  "averagePrice": "0.00",
  "cheaperItem": ""
}
```

#### Error Response Sample 

HTTP Response Code: 422

```json
{
  "message": "Duplicated Entry!"
}
```

### [DELETE] /supermarket/:id

##### URL Params

id=[integer|required]

#### Success Response Sample

HTTP Response Code: 200

```json
{
  "message": "Deleted!"
}
```

#### Error Response Sample 

HTTP Response Code: 404

```json
{
  "message": "Not found."
}
```

### [GET] /supermarkets

##### No Param object

id=[integer|required]

#### Success Response Sample

HTTP Response Code: 200

```json
[{
  "id": 3,
  "name": "Mercadona",
  "prices": [{
    "id": 2,
    "key": "carrots",
    "price": "0.80"
  }, {
    "id": 3,
    "key": "honey",
    "price": "4.50"
  }, {
    "id": 6,
    "key": "milk",
    "price": "1.80"
  }],
  "averagePrice": "2.36",
  "cheaperItem": "carrots"
}, {
  "id": 7,
  "name": "Carrefour",
  "prices": [{
    "id": 4,
    "key": "carrots",
    "price": "0.84"
  }, {
    "id": 5,
    "key": "milk",
    "price": "1.94"
  }],
  "averagePrice": "1.38",
  "cheaperItem": "carrots"
}]
```

### [POST] /price

##### Param object

supermaket_id=[integer|required]

name=[string|required]

price=[float|required]

##### Sample Request JSON

```json
{
  "supermaket_id": 1,
  "name": "Carrots",
  "price": 0.8
}
```

#### Success Response Sample

HTTP Response Code: 200

```json
{
  "id": 1,
  "key": "carrots",
  "price": "0.80",
  "supermarket": {
    "id": 1,
    "name": "Mercadona"
  }
}
```

#### Error Response Sample 

HTTP Response Code: 422

```json
{
  "message": "Supermarket not found"
}
```

### [DELETE] /price/:id

##### URL Params

id=[integer|required]

#### Success Response Sample

HTTP Response Code: 200

```json
{
  "message": "Deleted!"
}
```

#### Error Response Sample 

HTTP Response Code: 404

```json
{
  "message": "Not found."
}
```

### [GET] /price/:id

##### URL Params

id=[integer|required]

#### Success Response Sample

HTTP Response Code: 200

```json
{
  "id": 2,
  "key": "carrots",
  "price": "0.80",
  "supermarket": {
    "id": 3,
    "name": "Mercadona"
  }
}
```

#### Error Response Sample 

HTTP Response Code: 404

```json
{
  "message": "Not found."
}
```
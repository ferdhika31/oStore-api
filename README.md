# oStore API

Backend Engineer Assessment. Problem 2.

## Business Requirements

- [x] An Order consists of at minimum one Order Item.
- [x] The online store will hold a flash sale, in which customers will try to buy a Product at a heavily discounted price.
- [x] The system must be able to prevent a negative Inventory quantity value.

## Postman Documentation API

Check [Postman Documentation](https://documenter.getpostman.com/view/12023164/TVmS8awr) 

BaseURL [ostore.dika.web.id](http://ostore.dika.web.id) 

## Setup With Docker

### Setup

- `git clone https://github.com/ferdhika31/oStore-api.git`
- `cd oStore-api`
- `docker-compose build app`
- `docker-compose up -d`
- `docker-compose exec app composer install`
- `cp .env.example .env` or `copy .env.example .env`
- `docker-compose exec app php artisan key:generate`
- `docker-compose exec app php artisan migrate --seed`

Now that all containers are up, access from browser `localhost:8000`

### Running test suite race condition:

#### Reset data in Database
First, reset data in database.
```bash
docker-compose exec app php artisan migrate:fresh --seed
```

#### Generate data product
```bash
docker-compose exec app php artisan product:generate
```

#### Run parallel testing with paratest
```bash
docker-compose exec app ./vendor/bin/paratest -p8 tests/Feature/Order
```

### Stop 
- `docker-compose stop` to stop app


## Setup Without Docker

### Setup

- `git clone https://github.com/ferdhika31/oStore-api.git`
- `cd oStore-api`
- `composer install`
- `cp .env.example .env` or `copy .env.example .env`
- `php artisan key:generate`
- Edit database configuration
- `php artisan migrate --seed`
- `php artisan serve`

### Running test suite race condition:

#### Reset data in Database
First, reset data in database.
```bash
php artisan migrate:fresh --seed
```

#### Generate data product
```bash
php artisan product:generate
```

#### Run parallel testing with paratest
```bash
./vendor/bin/paratest -p8 tests/Feature/Order
```
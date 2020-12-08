# oStore API

Backend Engineer Assesment. Problem 2.

## Business Requirements

- [x] An Order consists of at minimum one Order Item.
- [x] The online store will hold a flash sale, in which customers will try to buy a Product at a heavily discounted price.
- [x] The system must be able to prevent a negative Inventory quantity value.

## Postman Docummentation API

Check [Postman Docummentation](https://documenter.getpostman.com/view/12023164/TVmS8awr) 

BaseURL [ostore.dika.web.id](http://ostore.dika.web.id) 


## Install

### Clone Project
```bash
# Clone this repo
git clone https://github.com/ferdhika31/oStore-api.git
```

### Change Directory to Project
```bash
cd oStore-api
```

### Copy .env.example file
```bash
cp .env.example .env
```

### Install dependency

```bash
composer install
```

### Generate APP Key
```bash
php artisan key:generate
```

### Fill Database Configuration 
- Fill **DB_HOST**
- Fill **DB_DATABASE**
- Fill **DB_USERNAME**
- Fill **DB_PASSWORD**

### Migrate and install Laravel Passport

```bash
# Create new tables for Passport
php artisan migrate --seed
```

### Start server
```bash
php artisan serve
```

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

## License

The Lumen framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
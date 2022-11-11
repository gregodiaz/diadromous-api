# Diadromous
An api to create fictional ship travels from one city to another -or even more- and validate them or not according to the [open weather forecast api.](https://open-meteo.com/en)
Just like the weather, the cities of the travel route are real! and are also requested from open-meteo of the [geocoding api.](https://open-meteo.com/en/docs/geocoding-api#geocoding_form)


## Installation
1. Clone project and go to the folder
```bash
git clone https://github.com/gregodiaz/diadromous-api.git && cd diadromous-api
```
2. Install the dependencies 
```bash
composer install
```
3. Create the .env
```bash
cp .env.example .env
```
4. In your .env set the database connections:
DB_CONNECTION, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD, 
5. Run
```bash
php artisan key:generate
php artisan migrate --seed
php artisan serve
```
6. Ready to go!


## Usage
to see the available routes, check the files .http in resources/request/v1/*.http

# Diadromous
An api to create fictional ship travels from one city to another -or even more- and validate them or not according to the [open weather forecast api.](https://open-meteo.com/en)
Just like the weather, the cities of the travel route are real! and are also requested from open-meteo of the [geocoding api.](https://open-meteo.com/en/docs/geocoding-api#geocoding_form)

## Prerequisites
- Have Docker installed
- Some relational database management system, like sqlite, mysql, postgres or sql server

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
5. Create a database in with the same name of the DB_DATABASE
6. Run
```bash
sail artisan key:generate
sail artisan migrate --seed
sail up
```
7. Ready to go!


## Usage
to see the available routes, check the .http files in resources/request/v1/*.http

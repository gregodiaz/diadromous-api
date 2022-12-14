# Diadromous
An api to create fictional ship travels from one city to another -or even more- and validate them or not according to the [open weather forecast api.](https://open-meteo.com/en)
Just like the weather, the cities of the travel route are real! and are also requested from open-meteo of the [geocoding api.](https://open-meteo.com/en/docs/geocoding-api#geocoding_form)

You can consume this api with the front-end version of the same project. Check out the front-end version! [Click here!](https://github.com/gregodiaz/diadromous-react)

## Prerequisites
- Have Docker installed

## Installation

1. Clone project and go to the folder
```bash
git clone https://github.com/gregodiaz/diadromous-api.git && cd diadromous-api
```

2. Install the dependencies 
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```

3. Create the .env
```bash
cp .env.example .env
```

4. If you already have a DBMS (database management system) or Apache running, turn it off your with the command
```bash
sudo service <DBMS name> stop
```
```bash
sudo service apache2 stop
```
_After using and testing the application, you can start the database again with the same command using the word 'start'_

5. Add line to crontab for task scheduling (_This step is not strictly necessary for the operation of the app_):
```
* * * * * cd /<path-to-your-project> && php artisan schedule:run >> /dev/null 2>&1
```

6. Start and seed de project. Run:
```bash
./vendor/bin/sail up
```
_If you see a message "Docker is not running" is because you need start Docker "rootless". You may check https://docs.docker.com/engine/install/linux-postinstall/_
_Also if you want it, you can replace the './vendor/bin/sail' adding the alias for 'sail' running in bash ```alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'```_
Open another termial and run:
```bash
./vendor/bin/sail artisan key:generate
```
```bash
./vendor/bin/sail artisan migrate --seed
```

7. Ready to go!


## Usage
To see the available routes, check the http files in ./resources/request/v1/*.http

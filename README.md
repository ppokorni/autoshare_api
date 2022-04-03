
<p align="center"><a href="#" target="_blank"><img src="/public/assets/img/autoshare_logo_banner.png" width="500"></a></p>

[//]: # ( This is a Laravel application acting as a backend and API for the Autoshare Android application)

## About this project

- Built using Laravel

- Serves as a backend API for the Autoshare Android application

[//]: # ( - Uses the Laravel Sanctum package to authenticate users)

## Documentation

You can find the documentation for this project [here](#).

## Requirements

PHP: ^7.4

Composer: ^2.0

Laravel: ^8.0

Database: Postgresql 12

## Getting started

Clone the repository:

    git clone git@github.com:ppokorni/autoshare_api.git

Navigate to the root of project directory, then run:

    composer install

Copy the .env.example file to .env and change the values to match your enviroment (database, app_url, etc.)

Run this command to generate the Application Key (APP_KEY in .env):

    php artisan key:generate

Confirm that the database exists and is accessible, then run: [More about migrations](https://laravel.com/docs/8.x/migrations#running-migrations)

    php artisan migrate

Finally, run:

    php artisan serve

Additionally, if you need access to your storage directory, you can run:

    php artisan storage:link

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

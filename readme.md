<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## Install

Алгитм розгортання на Homestead(Vagrant)
1. git clone https://MykolaV@bitbucket.org/MykolaV/map.test.git
2. composer install
3. cp .env.example .env
4. php artisan key:generate
5. php artisan storage:link
6. Редагувати налаштування в файлі .env
    Для прикладу:
    APP_URL=http://map.test
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=laravel
    DB_USERNAME=root
    DB_PASSWORD=
    MIX_APP_REST_API="http://map.test/api"
    php artisan migrate --seed
7. npm i
8. npm run prod


## License

The Laravel framework is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).

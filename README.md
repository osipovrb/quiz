## Quiz game
### Установка репо
```shell
$ git clone https://github.com/osipovrb/quiz
$ composer install
$ npm install
$ cp .env.example .env
$ php artisan key:generate
```
### Установка sqlite (при необходимости):
```shell
$ sudo apt install php-sqlite3
```
В php.ini раскомментируйте строку
```ini
exteinsion=pdo_sqlite
```
### .env
Отредактируйте строки согласно своим настройкам
```ini
DB_DATABASE=/absolute/path/to/db.sqlite
PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=
```
### Создание БД и компиляция JS
Создайте БД sqlite:
```shell
$ mkdir /absolute/path/to
$ touch /absolute/path/to/db.sqlite
```
Запустите миграции
```shell
$ php artisan migrate --seed
```
Скомпилируйте js
```shell
$ npm run dev
```
### Запуск сервера
```shell
$ php artisan serve
$ php aritsan queue:listen
$ php artisan websockets:serve
$ php artisan ticker:listen
$ php ./ticker.php
```
Перейдите по адресу http://127.0.0.1:8000
### Готово!
Автор Осипов Алексей, telegram @osipovrb

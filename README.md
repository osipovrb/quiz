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
### Создание БД и компиляция JS
Создайте файл БД:
```shell
$ mkdir /absolute/path/to
$ touch /absolute/path/to/db.sqlite
```
В файле .env укажите путь к БД
```ini
DB_DATABASE=/absolute/path/to/db.sqlite
```
Запустите миграции
```shell
$ php artisan migrate --seed
```
Соберите js
```shell
$ npm run dev
```
### Запуск сервера
```shell
$ php artisan serve
$ php artisan queue:listen
$ php artisan websockets:serve
$ php artisan ticker:listen
$ php ./ticker.php
```
Перейдите по адресу http://127.0.0.1:8000
### Готово!
tg @osipovrb

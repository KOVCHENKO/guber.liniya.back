1. git clone --branch dev http://git.dev-io.ru:3031/ilkov/guber.liniya.back.git
2. composer install
3. Создать файл в корне .env на основе .env.example
4. php artisan key:generate
5. В файле .env установить переменные: DB_DATABASE, DB_USERNAME, DB_PASSWORD
6. php artisan jwt:secret
7. Назначить папкам в корне права 777 (рекурсивно) : bootstrap, public, storage
8. Импортировать в бд файл в корне liniya3.sql
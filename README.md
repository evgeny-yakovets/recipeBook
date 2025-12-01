Remove containers:
docker compose down

Create containers:
docker compose up -d --build

Setup project:
docker compose exec app composer setup

Run tests:
docker compose exec app composer test

Emails for registered users stored in:
storage/logs/laravel.log

Generate api doc:
docker compose exec app php artisan l5-swagger:generate

Go to:
http://localhost:8080/

Project splited to separate docker containers - nothing needed to up manually except setup

Remove containers:
docker compose down

Create containers:
docker compose up -d --build

Install dependecies
docker compose exec app composer install
docker compose exec app npm install

Generate files:
docker compose exec app npm run build

Migrations:
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed

Run Vite dev-server:
docker compose exec app npm run dev -- --host

Run tests:
docker compose exec app php artisan test
docker compose exec app npx playwright test

Emails for registered users stored in:
storage/logs/laravel.log

Go to:
http://localhost:8080/
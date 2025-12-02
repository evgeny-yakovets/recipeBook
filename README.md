Remove containers:
docker compose down

Create containers:
docker compose up -d --build

Setup project:
docker compose exec php composer setup

Run tests in Git Bash:
./run-tests.sh

Emails for registered users stored in:
storage/logs/laravel.log

Generate api doc:
docker compose exec app php artisan l5-swagger:generate

Hot dev mode:
docker compose run --rm node npm run dev -- --host --port 5173 --strictPort

Build, if not in hot mode:
docker compose run --rm node npm run build

Go to:
http://localhost/

Email submit emails are stored in logs because of out of task scope
Project splited to separate docker containers - nothing needed to run manually except setup
Playwrite tests use main database - tests shoul use separate db in future
Also test database should be fresh and tests runs should be independed

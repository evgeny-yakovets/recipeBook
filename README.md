Remove containers:
docker compose down

Create containers:
docker compose up -d --build

Install dependecies
docker compose exec app composer install
docker compose exec node npm install

Generate files:
docker compose exec node npm run build

Run Vite dev-server:
docker compose exec node npm run dev -- --host

Go to:
http://localhost:8080/
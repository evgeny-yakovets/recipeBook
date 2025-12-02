#!/bin/bash
set -e  

echo "=== Running Laravel tests ==="
docker compose exec php composer test

echo "=== Running Playwright tests ==="
docker compose run --rm playwright npx playwright test

echo "=== All tests finished successfully ==="

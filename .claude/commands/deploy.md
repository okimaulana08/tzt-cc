# Deploy

Run the full deploy sequence for the current environment. Defaults to **local** (migrate + seed). Pass `prod` as $ARGUMENTS to run production-safe deploy.

## Pre-flight checks (always)

1. Run `php artisan test --ansi` — abort if any test fails
2. Run `./vendor/bin/pint --test` — abort if style errors found
3. Check for `dd(`, `dump(`, `var_dump(`, `ray(` in `app/` and `resources/` — abort if found

## Local / staging deploy

```bash
php artisan migrate --ansi
php artisan db:seed --ansi        # only if --seed flag passed
npm run build
php artisan optimize:clear
php artisan optimize
```

## Production deploy ($ARGUMENTS = prod)

```bash
composer install --no-dev --optimize-autoloader --no-interaction
php artisan migrate --force --ansi
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
npm ci && npm run build
php artisan queue:restart
```

## Post-deploy verification

- Confirm `php artisan route:list` shows no errors
- Confirm app key is set: `php artisan key:generate --show` (should already be set)
- Tail logs for 10 seconds: `php artisan log:watch` or `tail -f storage/logs/laravel.log`

## Rollback

If something breaks after deploy:
```bash
php artisan migrate:rollback       # revert last migration batch
php artisan optimize:clear         # clear all caches
```

## Output

Report each step's status (✅ passed / ❌ failed) and stop immediately on any failure, explaining what went wrong and how to fix it.

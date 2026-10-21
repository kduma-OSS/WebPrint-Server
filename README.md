# WebPrint Server

The application runs on **PHP 8.5**, **Laravel 13**, **Jetstream 5**, and **Livewire 3**.

## Development

Use Composer 2, Node.js 22.12 or newer, and MySQL 8.0. PHP needs the usual Laravel extensions, PDO MySQL, `mbstring`, and `intl`; development dependencies also require `bcmath`. SQLite is supported for tests.

```sh
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate
npm ci
npm run build
```

Laravel Sail uses PHP 8.5. Keep the existing `.env` values when upgrading an installation.

## Upgrading an existing installation

Back up the database and `storage/app/jobs`, and preserve the existing `APP_KEY`. Deploy the updated PHP code, Composer dependencies, and frontend assets together. All PHP services (FPM and cron) must use the new images.

```sh
php artisan optimize:clear
php artisan migrate --force
```

The new settings migration upgrades the existing table for `spatie/laravel-settings` 3: it adds a unique index on `(group, name)` and defaults `locked` to false. Existing setting values and lock states are retained. Resolve duplicate `(group, name)` records before migrating if the database contains any.

The Docker build script installs production dependencies, compiles the views, and builds the frontend assets. It no longer bypasses Composer platform requirements. Livewire 3 discovers components without the old `livewire:discover` command and supplies Alpine.js itself.

## Verification

```sh
composer validate --strict
composer test
composer test-pint
npm run build
```

Tests default to an in-memory SQLite database. To include JSON query scenarios that require MySQL, use a separate disposable test database:

```sh
DB_CONNECTION=mysql DB_DATABASE=testing DB_USERNAME=root DB_PASSWORD=testing composer test
```

CI runs the suite on PHP 8.5 with both SQLite and MySQL 8.0. The suite covers the printing APIs, bearer tokens, team permissions, panel components, settings migration, and scheduled print cleanup.

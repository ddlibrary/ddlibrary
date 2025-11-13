# Darakht‑e Danesh Online Library

'Darakht‑e danesh' means "knowledge tree" in Dari, one of the official languages of Afghanistan. The Darakht‑e Danesh Online Library for Educators is a repository of open educational resources for teachers, teacher trainers, school administrators, literacy workers and others involved in furthering education in Afghanistan. These open source resources include lesson plans, pedagogical tools, exercises, experiments, reading texts, work books, curricula and other resources for use in Afghan classrooms.

## Overview
This repository contains the web application that powers the Darakht‑e Danesh Library. It is a Laravel 10 application (PHP) with a Vue/JavaScript front‑end built using Laravel Mix (Webpack). It ships with a Dockerized development environment (Nginx + PHP‑FPM + MySQL + Redis).

## Tech Stack
- Language: PHP 8.1+ (composer platform set to 8.1)
- Framework: Laravel 10
- Front‑end: Vue 3, Bootstrap 5, jQuery; built with Laravel Mix (Webpack)
- Package managers: Composer (PHP), npm (Node)
- Database: MySQL 8 (dev via Docker; tests use SQLite in‑memory)
- Caching/Queues: Redis (via Docker) and Laravel queue; default sync driver in .env
- Storage: Local filesystem and AWS S3 via Flysystem
- Web server (dev): Nginx proxying to PHP‑FPM (Docker)

## Requirements
Choose one of the following setups.

Option A — With Docker (recommended for development):
- Docker Desktop 4.x+
- docker-compose v2+

Option B — Without Docker (native):
- PHP 8.1+ with extensions commonly required by Laravel (OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath, Fileinfo)
- Composer 2.x
- Node.js 16–20 and npm 8+
- MySQL 8 and Redis (optional for local queues/cache)

## Getting Started

### 1) Clone and bootstrap
```
git clone <this-repo-url> ddlibrary
cd ddlibrary
cp .env.local .env 
```

Update values in `.env` as needed (see Environment Variables below).

### 2A) Run with Docker
```
docker compose up -d --build
```
This starts:
- web: Nginx serving `public/` on http://localhost:8080
- app: PHP‑FPM container (port 9000)
- db: MySQL 8 (port 3306)
- redis: Redis (port 6379)

Inside the app container, install dependencies and set up the app:
```
docker compose exec app composer install
docker compose exec app php artisan key:generate
# Create storage symlink for public files
docker compose exec app php artisan storage:link
# Install JS dependencies and build assets
docker compose exec app npm ci
docker compose exec app npm run development
# Run database migrations (and seed if applicable)
docker compose exec app php artisan migrate
# docker compose exec app php artisan db:seed   # TODO: enable if seeds exist/required
```
Open http://localhost:8080

### 2B) Run natively (no Docker)
```
composer install
php artisan key:generate
php artisan storage:link
npm ci
npm run development
# Set up your database (create schema), then:
php artisan migrate
# php artisan db:seed   # TODO: enable if seeds exist/required
php artisan serve --host=127.0.0.1 --port=8080
```
App will be available at http://127.0.0.1:8080

## NPM Scripts
Defined in `package.json`:
- `npm run development` — Build assets in development mode
- `npm run watch` — Build and watch for changes
- `npm run watch-poll` — Watch with polling (useful in Docker/VMs)
- `npm run hot` — HMR via Mix
- `npm run production` — Build minified production assets

## Composer Scripts
Defined in `composer.json`:
- `post-root-package-install` — Copies `.env.local` to `.env` if not present
- `post-create-project-cmd` — Generates app key
- `post-autoload-dump` — Laravel package discovery
- `post-update-cmd` — Publishes Laravel assets

## Application Entry Points
- HTTP entry: `public/index.php` (served by Nginx in Docker, or PHP’s built‑in server via `php artisan serve`)
- Console/cron: `artisan` (Laravel CLI)

## Environment Variables
The `.env` file configures the app. Common keys (examples from the provided `.env`):

Core app
- `APP_NAME` — Application name
- `APP_ENV` — `local` | `staging` | `production`
- `APP_KEY` — Set via `php artisan key:generate`
- `APP_DEBUG` — `true`/`false`
- `APP_URL` — Base URL (e.g., http://localhost:8080)
- `PHP_VERSION` — Used by Docker build ARG

Database
- `DB_CONNECTION` — `mysql`
- `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`

Queues/Cache/Sessions
- `QUEUE_DRIVER` or `QUEUE_CONNECTION` — Defaults to `sync` locally
- Redis config — TODO: document if non‑defaults are required

Storage (S3)
- `FILESYSTEM_DISK` — `local` | `public` | `s3` (defaults to `local`)
- `AWS_ACCESS_KEY_ID`, `AWS_SECRET_ACCESS_KEY`
- `AWS_DEFAULT_REGION`, `AWS_BUCKET`, `AWS_URL`, `AWS_ENDPOINT`, `AWS_USE_PATH_STYLE_ENDPOINT`
- `S3_OBJECT_ACCESS_SECRET` — App‑specific secret used for resource access

Misc
- `LOG_CHANNEL` — Default `stack`
- `CAPTCHA` — `no` or provider flag (usage in app code)
- Social login (Google/Facebook via Socialite) —
  - TODO: add `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, `GOOGLE_REDIRECT_URI`
  - TODO: add `FACEBOOK_CLIENT_ID`, `FACEBOOK_CLIENT_SECRET`, `FACEBOOK_REDIRECT_URI`
- Analytics/other integrations (Spatie Analytics, etc.) — TODO: list required keys if used

## Running Tests
PHPUnit is configured in `phpunit.xml`.

Local (native):
```
php artisan test
# or
./vendor/bin/phpunit
```

Docker:
```
docker compose exec app php artisan test
# or
docker compose exec app ./vendor/bin/phpunit
```
Tests use an in‑memory SQLite database as configured in `phpunit.xml`.

## Project Structure
High‑level layout:
- `app/` — Laravel application (controllers, models, console, etc.)
- `bootstrap/` — Framework bootstrap and cache
- `config/` — App configuration (see `filesystems.php` for S3 config)
- `database/` — Migrations, seeders, factories
- `public/` — Web root; front controller `index.php`
- `resources/` — Views (Blade), assets
- `routes/` — Route definitions (see `routes/web.php`)
- `storage/` — Logs, cache, compiled views, and `app/public` for public files
- `tests/` — Unit and Feature tests
- `docker/` — Docker config (Nginx, PHP, MySQL)
- `ddl-lite/` — Project‑specific assets/tools (TODO: document usage)
- `docs/` — Contribution and other documentation

## Database (migrations + seeders/factories)
This project uses Laravel migrations, seeders, and model factories to set up local data. 

Seed the database after running migrations:

Native:
```
php artisan migrate --seed
# To reset and reseed:
php artisan migrate:fresh --seed
```

Docker:
```
docker compose exec app php artisan migrate --seed
# To reset and reseed:
docker compose exec app php artisan migrate:fresh --seed
```

Notes:
- Seeders live in `database/seeders` (e.g., `RoleSeeder`, `SettingsSeeder`, `SubjectAreaSeeder`, `LearningResourceTypeSeeder`, etc.).
- Model factories live in `database/factories` and are used by seeders to generate sample data.
- If you need a larger or smaller dataset, adjust the seeder counts in `DatabaseSeeder` or run specific seeders:
  - Native: `php artisan db:seed --class=Database\\Seeders\\YourSeeder`
  - Docker: `docker compose exec app php artisan db:seed --class=Database\\Seeders\\YourSeeder`
- Legacy `dump.sql` remains in the repo for reference but is deprecated for local dev. Prefer factories/seeders.

## Common Artisan Commands
- `php artisan migrate` — Run migrations
- `php artisan storage:link` — Create `public/storage` symlink
- `php artisan tinker` — REPL
- `php artisan queue:work` — Run queue worker (if using a queue driver)

## Deployment
- Build production assets: `npm run production`
- Ensure correct `.env` for environment and set `APP_KEY`
- Configure `FILESYSTEM_DISK` and S3 credentials if storing on S3
- Run database migrations
- Serve `public/` via a web server (Nginx/Apache) pointing PHP‑FPM to `artisan`

## License


## To Contribute
We welcome contributions through PRs that includes code enhancements, new features or any other improvements to the codebase. Please check how you can [contribute](docs/contributing.md) and how you can [submit a pull request](docs/pull-request-guideline.md).

# VikAPP — Laravel Project Skeleton

A lightweight Laravel application skeleton used for teaching and rapid development.  
Provides a basic structure for clubs, raids, races, teams and runners with a CI deployment flow for three protected branches.

## Features
- Club, Raid, Race, Team and Runner models and basic CRUD
- Role-aware management views (admins, club managers, race/raid organizers)
- Simple CI deployment pipeline tied to branches
- Tailwind + Vite frontend build

## Branches & Deployment
- `main` — initial baseline (protected).
- `dev` — staging branch for active development.
- `stable` — release branch; updates here trigger CI deployment:
  - copy `.env.prod` → `.env` and generate app key
  - run composer/npm installs when needed
  - build frontend assets
  - run database migrations

Do not push secret files (like `.env`) to the repository.

## Requirements
- PHP 8.x
- Composer
- Node.js & npm
- SQLite (default) or MySQL/Postgres
- Git

## Quick start (Linux)
1. Clone repository:
   git clone <repo-url> && cd VikAPP
2. Install PHP dependencies:
   cd laravel
   composer install
3. Install JavaScript dependencies:
   npm install
4. Copy environment:
   cp .env.example .env
5. adjust DB settings if needed (in .env)
6. Generate key:
   php artisan key:generate
7. Run migrations:
   php artisan migrate
8. Build assets (development):
   npm run dev
9. Serve (local):
   php artisan serve

## Useful commands
- List routes:
  php artisan route:list
- Clear compiled views:
  php artisan view:clear
- Run tests:
  ./vendor/bin/phpunit
- Production build:
  npm run build

## Database
- Default: SQLite (configured in .env.example)
- To use MySQL/Postgres, update .env DB_* variables and run migrations.

## Project layout (important folders)
- laravel/app — Models, Controllers, Services
- laravel/resources/views — Blade templates (partials, pages)
- laravel/routes/web.php — main web routes
- laravel/public — public assets
- laravel/database/migrations — DB schema
- laravel/tests — automated tests

## Contributing
- Work on `dev` branch: create a feature branch, implement, commit, push, and open a merge request into `dev`.
- After testing on `dev`, merge into `stable` for deployment.
- Keep `.env` and other secrets out of the repo.

## Troubleshooting
- If a route is missing, run:
  php artisan route:list | grep <name>
- If views don't reflect changes:
  php artisan view:clear
- If migrations fail, check .env DB configuration and run:
  php artisan migrate:status

## Notes
- CI pipeline is configured to run on merges to `stable`. Review `.gitlab-ci.yml` in the repository to adapt steps.
- Check `laravel/app/Models` for database column names when building or fixing views/controllers.

For more specific guidance or to add documentation for a given feature, provide the area to document (controllers, routes, or deployment) and a short description of desired content.



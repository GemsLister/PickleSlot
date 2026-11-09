# PickleSlot

PickleSlot is a Laravel application for browsing pickleball courts and rental equipment, reserving court time, and managing facility inventory.

## Features

- Browse courts and rental equipment.
- Register, sign in, and manage a player profile.
- Check availability and book one or more courts for a time slot.
- Add paddle and ball rentals to a reservation.
- View and manage personal reservations.
- Administer courts, equipment, and reservation statuses through the admin area.
- Restrict booking actions to player accounts; administrators use the facility management area.

## Requirements

- PHP 8.3 or later (the Docker image uses PHP 8.5)
- Composer 2
- Node.js 22 and npm
- PostgreSQL for a PostgreSQL-backed local setup, or SQLite for a lightweight local setup

## Local development

1. Install PHP and JavaScript dependencies:

   ```sh
   composer install
   npm install
   ```

2. Create your local environment file and application key:

   ```sh
   cp .env.example .env
   php artisan key:generate
   ```

   On Windows PowerShell, use `Copy-Item .env.example .env` instead of `cp`.

3. Configure the database in `.env`.

   For SQLite, set:

   ```dotenv
   DB_CONNECTION=sqlite
   DB_DATABASE=database/database.sqlite
   ```

   The repository includes `database/database.sqlite`. If it is missing, create an empty file there before migrating.

   For PostgreSQL, set `DB_CONNECTION=pgsql` and provide `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` for your local database. Never commit real credentials.

4. Run migrations:

   ```sh
   php artisan migrate
   ```

5. Start the Laravel and Vite development servers in separate terminals:

   ```sh
   php artisan serve
   ```

   ```sh
   npm run dev
   ```

   Open `http://127.0.0.1:8000`.

To compile frontend assets for production:

```sh
npm run build
```

## Tests

Run the Pest test suite with:

```sh
php artisan test
```

## Seed data

The database seeders provide sample courts and equipment:

```sh
php artisan db:seed --class=CourtSeeder --force
php artisan db:seed --class=EquipmentSeeder --force
```

The default `DatabaseSeeder` also creates demo player and administrator accounts with a shared default password, plus sample reservations. Use it only in a local or disposable database:

```sh
php artisan db:seed
```

Do not run the default seeder in production. In particular, do not use `php artisan migrate:fresh` against a database with data you need; it drops existing tables.

## Deploy to Render

The repository includes a multi-stage `Dockerfile` that builds the Vite assets, installs production PHP dependencies, serves Laravel with Apache, enables PostgreSQL support, and runs migrations before starting Apache.

1. Push the deployment files to the Git branch you intend to deploy. In Render, create a **Web Service** from this GitHub repository and select **Docker** as the runtime. Use the repository root as the root directory.
2. Choose a Render region. If using Render Postgres, put the web service and database in the same region.
3. Add these environment variables to the web service:

   | Key | Value |
   | --- | --- |
   | `APP_NAME` | `PickleSlot` |
   | `APP_ENV` | `production` |
   | `APP_DEBUG` | `false` |
   | `APP_KEY` | Generate locally with `php artisan key:generate --show`; keep it secret |
   | `DB_CONNECTION` | `pgsql` |
   | `DB_URL` | The database connection URL. Use Render Postgres's internal URL, or your PostgreSQL provider's connection string |
   | `SESSION_DRIVER` | `database` |
   | `CACHE_STORE` | `database` |
   | `QUEUE_CONNECTION` | `database` |

   With Neon, use its PostgreSQL connection string, which should include `sslmode=require`. Do not paste database credentials into source files or commit them.

4. Leave the Docker build and start commands to Render's Docker integration. The image listens on port 80. The container runs `php artisan migrate --force` at startup.
5. After Render assigns the public service URL, set `APP_URL` to that full HTTPS URL, such as `https://your-service.onrender.com`.
6. To initialize production catalog data, use the Render Shell to run the `CourtSeeder` and `EquipmentSeeder` commands shown in [Seed data](#seed-data). Avoid the default `DatabaseSeeder`, which creates demo users.
7. Register your first account through the app, then use the Render Shell to promote that account to administrator. Replace the email with the address you registered:

   ```sh
   php artisan tinker --execute='App\Models\User::where("email", "admin@example.com")->update(["is_admin" => true]);'
   ```

   Registration creates player accounts by default. Keep administrator access limited to trusted accounts.

Uploaded public files are stored under `storage/app/public`. If the app needs uploaded files to survive deployments, attach persistent storage at `/var/www/html/storage/app/public` in Render.

## Environment and secrets

`.env` is local-only and must not be committed. Use Render's environment-variable settings for production configuration and secrets. If a database password or application key is exposed, rotate it.

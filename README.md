# FoodFusion

FoodFusion is a Laravel-based community for home cooks and creators — publish and browse recipes, learn cooking techniques, download recipe cards, and watch instructional videos.

---

## Quick links

- Routes: [routes/web.php](routes/web.php)  
- Culinary resources page: [resources/views/resource/culinary.blade.php](resources/views/resource/culinary.blade.php)  
- Import demo resources command: [app/Console/Commands/ImportDemoResources.php](app/Console/Commands/ImportDemoResources.php)  
- Resource model: [`App\Models\Resource`](app/Models/Resource.php)  
- Controller (loads resources): [`App\Http\Controllers\PageController::culinaryResources`](app/Http/Controllers/PageController.php)  
- Deployment script: [scripts/deploy.sh](scripts/deploy.sh)  
- Security policy: [SECURITY.md](SECURITY.md)

---

## Requirements

- PHP ^8.2
- Composer
- Node.js + npm (or pnpm)
- MySQL (recommended) or SQLite for local development
- Optional: Redis (caching, queues)
- Optional: S3-compatible storage for media in production
- Recommended OS: Linux/macOS (works on Windows with WSL)

Key composer packages (see [composer.json](composer.json)):

- laravel/framework
- laravel/socialite
- blade-ui-kit/blade-heroicons
- brunocfalcao/blade-feather-icons
- davidhsianturi/blade-bootstrap-icons

Key npm packages and build (see [package.json](package.json)):

- Vite-based frontend: run `npm install` and `npm run build` / `npm run dev`

Container/infra:

- Dockerfile present at [Dockerfile](Dockerfile)
- Deployment helper: [scripts/deploy.sh](scripts/deploy.sh)

Security:

- Follow [SECURITY.md](SECURITY.md)
- Ensure `APP_KEY` is set in `.env` and `APP_DEBUG=false` in production
- Run dependency audits: `composer audit` and `npm audit`

---

## Setup (local development)

1. Clone repo and enter directory

   ```sh
   git clone <repo> && cd FoodFusion
   ```

2. Copy environment template and edit

   ```sh
   cp .env.example .env
   # edit .env: DB_*, APP_KEY (auto-generated below), mail settings, storage disk
   ```

3. Install PHP deps

   ```sh
   composer install
   ```

4. Install JS deps and build assets

   ```sh
   npm install
   # development
   npm run dev
   # or production build
   npm run build
   ```

5. Generate app key

   ```sh
   php artisan key:generate
   ```

6. Create database and run migrations

   ```sh
   php artisan migrate
   # optionally seed (if seeders available)
   php artisan db:seed
   ```

7. Link storage for public assets

   ```sh
   php artisan storage:link
   ```

8. Serve locally

    ```sh
    php artisan serve
    ```

---

## Production / Deploy

- Build assets: `npm run build`
- Composer install with optimized autoloader: `composer install --no-dev --optimize-autoloader`
- Run migrations: `php artisan migrate --force`
- Cache config/routes/views: `php artisan config:cache && php artisan route:cache && php artisan view:cache`
- Use the provided deployment helper inside your container: [scripts/deploy.sh](scripts/deploy.sh)
- Ensure HTTPS termination, secure cookies, and `APP_DEBUG=false`.

---

## Data model notes

- Read-only resources (Culinary / Educational) are represented by `App\Models\Resource` ([app/Models/Resource.php](app/Models/Resource.php)).
- Pages that list resources are wired in `PageController` — see [`App\Http\Controllers\PageController::culinaryResources`](app/Http/Controllers/PageController.php).
- Routes: resource listing and detail routes are declared in [routes/web.php](routes/web.php).

---

## Admin / Content

- This app currently expects resource rows to be created by an admin or via the import command (`php artisan import:demo-resources`). Audience users view/download only.
- To add/manage resources manually, create admin-only CRUD or add records via tinker/seeders.

---

If you want, I can:

- Add a `Resource` seeder and factory.
- Add an admin-only controller + simple Blade form for uploading resources.
- Provide a small checklist for hardening production settings (env, HTTPS, backups).

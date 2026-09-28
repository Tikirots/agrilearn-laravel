# AgriLearn — Converted to Laravel

This converts your working plain-PHP/PDO build into Laravel, keeping your
**exact schema and features**: self-registration with admin approval,
per-program enrollment/approval, module upload + visibility toggle,
activity recording, certificates with QR verification, and the
notification bell.

Wala pang **Online Examination** dito — wala pa kasi nun sa naka-upload
niyong build. Susunod na phase 'yon (kasama sa roadmap natin), babase sa
mismong module/program structure na nandito na.

## 1. Merge into your Laravel project

Kung wala pa kayong ginawang `composer create-project laravel/laravel agrilearn`,
gawin muna 'yon (tingnan ang README sa unang starter zip). Pagkatapos:

- `database/migrations/*.php` → `agrilearn/database/migrations/`
- `database/seeders/DatabaseSeeder.php` → papalitan ang existing
- `app/Models/*.php` → `agrilearn/app/Models/` (papalitan ang default `User.php`)
- `app/Http/Controllers/**` → `agrilearn/app/Http/Controllers/`
- `app/Http/Middleware/*.php` → `agrilearn/app/Http/Middleware/`
- `app/Services/NotificationService.php` → `agrilearn/app/Services/`
- `app/Support/helpers.php` → `agrilearn/app/Support/`
- `resources/views/**` → `agrilearn/resources/views/` (papalitan ang default welcome page)
- `routes/web.php` → papalitan ang existing
- `config/agrilearn.php` → `agrilearn/config/`
- `public/assets/**` at `public/manifest.json` → `agrilearn/public/`

## 2. Register the global helpers (composer.json)

```json
"autoload": {
    "files": ["app/Support/helpers.php"]
}
```
Then: `composer dump-autoload`

## 3. Register middleware

**Laravel 11+** (`bootstrap/app.php`, inside `withMiddleware`):
```php
$middleware->alias([
    'role' => \App\Http\Middleware\RoleMiddleware::class,
    'active' => \App\Http\Middleware\EnsureAccountActive::class,
]);
```

**Laravel 10 and older** (`app/Http/Kernel.php`, `$middlewareAliases`):
```php
'role' => \App\Http\Middleware\RoleMiddleware::class,
'active' => \App\Http\Middleware\EnsureAccountActive::class,
```

## 4. Database + storage

```bash
# .env: set DB_DATABASE=agrilearn_db, DB_USERNAME, DB_PASSWORD
php artisan migrate
php artisan db:seed          # creates admin / admin123 (hashed properly this time)
php artisan storage:link     # so trainee photos are reachable at /storage/photos/...
mkdir -p storage/app/modules # uploaded module files (private, served through the app)
```

## 5. Important changes from your PHP version

- **Password security fixed.** Your `database.sql` inserted the default
  admin password as **plain text**. The Laravel seeder now uses
  `Hash::make()`. Flag this in your documentation as a fixed vulnerability,
  not a hidden one.
- **Sessions/Auth** now use Laravel's built-in `Auth` facade instead of
  manually managing `$_SESSION['user']`.
- **Flash messages** now use Laravel's native `session()->flash()` instead
  of your custom `set_flash()/get_flash()`.
- **Role check**: your original `is_admin()` allowed both `admin` and
  `trainer` into the admin side. This is preserved via
  `role:admin,trainer` in `routes/web.php` — but note your original code
  never actually had a way to *create* a `trainer` account (only `admin`
  and `trainee` show up in `database.sql`'s insert and `auth/register.php`).
  Worth deciding whether trainer accounts get their own registration/admin
  screen, since right now they're functionally identical to admin.
- **File streaming** (`module_stream.php` → `TraineeModuleController::stream`)
  keeps the same access checks (module visible + trainee approved for that
  program) before serving the file inline.
- **QR codes** still use the free `api.qrserver.com` image API (same as
  your original `qr_code_url()`), so your deployed server needs outbound
  internet access for certificates and profile QR images to render.

## 6. Still to convert manually

- `reset_admin.php` and `migration_approval.sql` are no longer needed —
  the seeder replaces the first, and the migrations already include
  everything the second one patched in.
- Double check `assets/css/style.css` for any selectors that assumed
  `/agrilearn/...` as a URL prefix (your old `BASE_URL`) — Laravel serves
  from the domain root by default, so drop that prefix if you find it
  hardcoded anywhere in the CSS/JS.

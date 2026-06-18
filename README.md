# Topper's Hope

Laravel web application for Topper's Hope (courses, faculty, payments, HR, and related features).

---

## Prerequisites

| Tool         | Version      | Purpose                                    |
| ------------ | ------------ | ------------------------------------------ |
| **PHP**      | 8.2+         | Laravel backend                            |
| **Composer** | Latest       | PHP dependencies                           |
| **Node.js**  | 18+ (LTS)    | Frontend (Vite)                            |
| **npm**      | With Node.js | JS dependencies                            |
| **MySQL**    | 5.7+ or 8.x  | Database (sessions & cache use DB locally) |

**PHP extensions:** `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`

On **Windows**, [Laragon](https://laragon.org/) or [XAMPP](https://www.apachefriends.org/) are common for PHP + MySQL.

---

## Quick start (new machine)

### Option A — One command (recommended)

1. Clone the repo and `cd` into the project folder.
2. Start **MySQL**.
3. Create an empty database (see step 4 below).
4. Copy `.env.example` to `.env` and set `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.
5. Run:

```bash
composer run setup
```

This runs: `composer install` → app key → migrations → seeders → `storage:link` → `npm install` → `npm run build`.

6. Start the app:

```bash
php artisan serve
```

Open [http://127.0.0.1:8000](http://127.0.0.1:8000)

### Option B — Step by step

Follow **Running the application** below if you prefer manual control.

---

## Default logins (after `db:seed`)

Password for all seeded accounts: **`Admin@123`** (override with `ADMIN_SEED_PASSWORD` in `.env` before seeding).

| Panel        | URL              | Email                         |
| ------------ | ---------------- | ----------------------------- |
| Super Admin  | `/admin/login`   | `admin@toppershope.com`       |
| Faculty Head | `/faculty/login` | `facultyHead@toppershope.com` |

Other panel users (HR, faculty, ads, admission) are created from **Admin → HR Users** or **HR → Employees → Panel Access**.

---

## Running the application (manual setup)

Run commands from the **project root** (folder that contains `artisan`).

### 1. Clone & install dependencies

```bash
git clone <repository-url> toppershope-website
cd toppershope-website
composer install
npm install
```

### 2. Environment (`.env`)

```bash
cp .env.example .env   # Linux / macOS / Git Bash
```

**Windows (PowerShell):**

```powershell
Copy-Item .env.example .env
```

Edit `.env` — minimum for local dev:

```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=toppers_hope
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
MAIL_MAILER=log
```

Generate the app key:

```bash
php artisan key:generate
php artisan config:clear
```

### 3. Create the database

In MySQL:

```sql
CREATE DATABASE toppers_hope CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

`DB_*` values in `.env` must match your MySQL user and this database name.

### 4. Migrate & seed

```bash
php artisan migrate
php artisan db:seed
```

Fresh database (drops all tables):

```bash
php artisan migrate:fresh --seed
```

### 5. Storage link (uploads / public files)

Laravel serves uploaded files from `storage/app/public` via a symlink at `public/storage`.

```bash
php artisan storage:link
```

You should see:

```text
INFO  The [public/storage] link has been connected to [storage/app/public].
```

**No** `The system cannot find the path specified.` error.

#### Windows: `storage:link` fails

That message usually means one of these:

| Cause                                                               | Fix                                                                                                        |
| ------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------- |
| `storage/app/public` missing                                        | Ensure the folder exists (clone should include it). If not: `mkdir storage\app\public`                     |
| `public\storage` already exists as a **normal folder** (not a link) | Delete it, then link again (see below)                                                                     |
| Symlinks blocked                                                    | Enable **Developer Mode** (Settings → System → For developers), **or** run PowerShell **as Administrator** |

**PowerShell fix (run from project root):**

```powershell
# 1. Ensure upload target exists
New-Item -ItemType Directory -Force -Path storage\app\public

# 2. Remove old/broken public\storage (folder or bad link)
if (Test-Path public\storage) { Remove-Item public\storage -Recurse -Force }

# 3. Create the symlink
php artisan storage:link
```

If `php artisan storage:link` still fails, create the link manually (PowerShell **as Administrator**):

```powershell
New-Item -ItemType SymbolicLink -Path public\storage -Target storage\app\public
```

### 6. Frontend assets

**Production-style build:**

```bash
npm run build
```

**Development (hot reload — keep running in a second terminal):**

```bash
npm run dev
```

### 7. Start the server

```bash
php artisan serve
```

Open [http://127.0.0.1:8000](http://127.0.0.1:8000)

### 8. Full dev stack (optional)

Runs server, queue, logs, and Vite together:

```bash
composer run dev
```

### 9. Queue worker (if not using `composer run dev`)

```bash
php artisan queue:work
```

---

## Command reference

| Step                      | Command                    |
| ------------------------- | -------------------------- |
| PHP packages              | `composer install`         |
| JS packages               | `npm install`              |
| Full local setup          | `composer run setup`       |
| App key                   | `php artisan key:generate` |
| Database                  | `php artisan migrate`      |
| Seed admin + faculty head | `php artisan db:seed`      |
| Public storage link       | `php artisan storage:link` |
| Build assets              | `npm run build`            |
| Dev assets                | `npm run dev`              |
| Web server                | `php artisan serve`        |
| All-in-one dev            | `composer run dev`         |

---

## Troubleshooting

| Problem                                                          | What to do                                                              |
| ---------------------------------------------------------------- | ----------------------------------------------------------------------- |
| `Connection refused` (MySQL)                                     | Start MySQL; check `DB_HOST` / `DB_PORT`                                |
| `Access denied` (database)                                       | Fix `DB_USERNAME` / `DB_PASSWORD` in `.env`                             |
| `No application encryption key`                                  | `php artisan key:generate`                                              |
| Styles / JS missing                                              | `npm run build` or `npm run dev`                                        |
| Config stuck after `.env` change                                 | `php artisan config:clear`                                              |
| **`storage:link` — "The system cannot find the path specified"** | See [Windows storage:link fix](#windows-storagelink-fails) above        |
| Uploads / images 404                                             | Fix `public/storage` symlink, then `php artisan storage:link`           |
| Session / cache errors                                           | MySQL must be running (`SESSION_DRIVER` and `CACHE_STORE` use database) |
| `migrate` fails on empty DB                                      | Create the database first (`CREATE DATABASE ...`)                       |

---

## Local vs production

| Environment    | Use                                                         |
| -------------- | ----------------------------------------------------------- |
| **Local**      | `.env` with `APP_ENV=local`, local DB, test Razorpay keys   |
| **Production** | Copy production `.env` on the server only — never commit it |

Never commit `.env` to git.

---

## Optional integrations

- **Google OAuth** — `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, `GOOGLE_REDIRECT_URI` (must match Google Cloud Console).
- **Razorpay** — Test keys locally; live keys only in production.

---

## License

Based on [Laravel](https://laravel.com) (MIT license).

# Kargamine — Logistics CRM, Document Tracking & Finance Management

A Laravel 10 (PHP 8.1+) application for document tracking, CRM/lead management, client proposals & contracts, and finance tracking for a logistics/freight business (containers, lanes, ports, trucking tariffs, bookings). Server-rendered Blade views with jQuery/Alpine.js + Tailwind on the frontend, built via Vite.

---

## Tech Stack

- **Backend:** PHP 8.1+, Laravel 10
- **Database:** MySQL 5.7+
- **Frontend:** Blade templates, jQuery, Alpine.js, Tailwind CSS
- **Build tool:** Vite (single entry point, `resources/js/app.js`)
- **PDF generation:** barryvdh/laravel-dompdf
- **Notable JS libraries:** DataTables, Chart.js, jsPDF + autotable, Leaflet, Swiper, jsQR/QRCode, Glide
- **Package managers:** Composer (PHP), npm (JS)

---

## Application Overview

The app allows users to:
- Manage CRM leads through to client conversion (`CrmLead` → `ClientMaster` → `ClientProposal` → `ClientContract`)
- Track documents within the organization (approvals, status, destinations)
- Manage bookings, containers, lanes, ports, and trucking tariffs
- Keep finance records linked to documents and contracts
- Generate reports across documents, CRM, and finance

The app shell (`resources/views/dashboard.blade.php`) is rendered once per session; page content is then loaded client-side via AJAX fragments (`/page_*` routes) — see `CLAUDE.md` for architecture details.

---

## Initial Setup (Local Development)

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd kargamine_prototype
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies** (Node v16+ recommended)
   ```bash
   npm install
   ```

4. **Set up your environment file**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Then edit `.env` with your local database credentials (see sample below).

5. **Run database migrations**
   ```bash
   php artisan migrate
   ```

6. **Seed the database** (creates the initial admin user and reference data)
   ```bash
   php artisan db:seed
   ```

7. **Build frontend assets**
   ```bash
   npm run build
   # or, for local development with hot-reload:
   npm run dev
   ```

8. **Serve the app**
   ```bash
   php artisan serve
   ```

You should now be able to log in with the seeded superadmin account:

- **Email:** `superadmin@email.com`
- **Password:** `Testing123`

---

## Sample `.env` (local development)

```env
APP_NAME=Kargamine
APP_ENV=local
APP_KEY=base64:GENERATE_YOUR_KEY
APP_DEBUG=true
APP_URL=http://localhost:8000

LOG_CHANNEL=stack

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kargamine
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

---

## Running Tests

Tests use PHPUnit with an in-memory SQLite database (see `phpunit.xml`).

```bash
php artisan test                          # full suite
php artisan test --filter=AuthFlowTest    # single test class
php artisan test tests/Feature/AuthFlowTest.php
vendor/bin/pint                           # code style (Laravel Pint)
```

There is no JS test runner or linter configured — `npm run build` / `npm run dev` are the only frontend scripts.

---

## Production Deployment

**Important:** Always run `npm run build` before deploying/restructuring files for production. Do not upload `node_modules/`, `.git/`, or your `.env` file to the hosting server.

### Recommended file structure

```
root/
│
├─ app_core/          <-- All Laravel framework files
│  ├─ app/
│  ├─ bootstrap/
│  ├─ config/
│  ├─ database/
│  ├─ resources/
│  ├─ routes/
│  ├─ storage/
│  ├─ vendor/
│  └─ ...other Laravel files
│
└─ public/             <-- Web root / frontend entry point
   ├─ index.php
   ├─ css/
   ├─ js/
   └─ ...other public assets
```

- `app_core/` contains all backend logic and Laravel files.
- `public/` should be the web-accessible root.
- Update the paths in `public/index.php` to point to `../app_core/` instead of the default `../`.

### Sample `.env` (production)

Create this file directly on the production server — never commit it to Git.

```env
APP_NAME=Kargamine
APP_ENV=production
APP_KEY=base64:GENERATE_YOUR_KEY
APP_DEBUG=false
APP_URL=https://your-domain.com
ASSET_URL=https://your-domain.com   # required so the built JS/CSS resolve to the right domain

LOG_CHANNEL=stack

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

Generate `APP_KEY` on the server with:
```bash
php artisan key:generate
```

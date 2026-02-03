# Document Tracking & Finance Management System

A Laravel 10 application for **document tracking and management**, including **finance tracking**.

---

## Tech Stack

- **PHP:** 8.2 or later
- **MySQL:** 5.7 or higher
- **Composer** (PHP dependency manager)
- **Node.js** (v16+ recommended)
- **Laravel:** 10
- **Frontend build tools:** npm (for assets compilation)

---

## Application Overview

This application allows users to:

- Track documents within an organization
- Manage approvals, status, and destinations
- Keep finance records linked to documents
- Generate reports on both documents and finance transactions

---

## Installation / Migration Procedure

After pulling the repository:

1. **Install PHP dependencies**

```bash
composer install

Install Node.js dependencies
npm install

Run database migrations
php artisan migrate

Build frontend assets
npm run build

Seed the database (creates the first admin/user to initialize the system)
php artisan db:seed

You are now ready to access the application.

the initial user is
user: superadmin@email.com
password: Testing123

Deployment File Structure
Important: Run npm run build before restructuring files for deployment.

root/
│
├─ app_core/           <-- All Laravel framework files
│   ├─ app/
│   ├─ bootstrap/
│   ├─ config/
│   ├─ database/
│   ├─ resources/
│   ├─ routes/
│   ├─ storage/
│   ├─ vendor/
│   └─ ...other Laravel files
│
└─ public/             <-- Frontend entry point
    ├─ index.php
    ├─ css/
    ├─ js/
    └─ ...other public assets
Notes:

app_core contains all backend logic and Laravel files

public/ should be accessible at the web root

public/index.php update the /../ to /app_core/ to point it to right folder.

"Do not include node_modules, .git folder and ENV when uploading to the hosting! thank you!"

Always run npm run build before moving files

Environment Setup (Production)
Create a .env file on the production server only. Do not push .env to Git.

Sample .env structure:

APP_NAME=DocumentTrackingApp
APP_ENV=production
APP_KEY=base64:GENERATE_YOUR_KEY
APP_DEBUG=false
APP_URL=https://your-domain.com
ASSET_URL=https://your-domain.com //this is crucial becuase the template need to point to your domain in order to get the js and css from build.

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
Use php artisan key:generate on the server to generate a secure APP_KEY.

Git Guidelines
Do not push your .env file

Always pull from origin/main before starting work:

git pull origin main
Commit changes with descriptive messages

git add .
git commit -m "Feature: Added document status filter"
git push origin main
For large changes, consider creating a feature branch first

First-time Setup Checklist (Quick Start)
git clone <repository-url>
cd <project-folder>
composer install
npm install
php artisan migrate
npm run build
php artisan db:seed
After this, your application should be ready to run.


This version is **fully GitHub Markdown compatible** and includes:

- Installation & migration steps
- Deployment file structure diagram
- `.env` instructions
- Git guidelines
- Quick-start checklist

---
```

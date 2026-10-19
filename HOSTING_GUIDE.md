# Hostinger Shared Hosting Deployment Guide (GitHub Workflow)

This guide walks you through deploying this Laravel application to **Hostinger Shared Hosting** (cPanel / hPanel) using a **GitHub Push -> Clone -> Configure** workflow with the secure split-directory structure:
- **Application Core & Logic**: `/home/uXXXX/admsys/` (Cloned Git Repository, inaccessible via web)
- **Public Web Root**: `/home/uXXXX/public_html/` (Served by web server)

```
/home/uXXXX/
├── admsys/                         <-- Cloned from GitHub (private repo)
│   ├── app/
│   ├── config/
│   ├── database/
│   ├── public/                     <-- Source public templates & build assets
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── vendor/                     <-- Installed via Composer
│   ├── .env                        <-- Production config (NEVER in Git)
│   ├── artisan
│   └── deploy.sh                   <-- One-click update script
│
└── public_html/                    <-- Public document root
    ├── build/                      <-- Synced from admsys/public/build
    ├── css/                        <-- Synced from admsys/public/css
    ├── images/                     <-- Synced from admsys/public/images
    ├── storage/                    <-- Symlink to ../admsys/storage/app/public
    ├── .htaccess                   <-- HTTPS & URL routing
    └── index.php                   <-- Modified entry point pointing to admsys
```

> **Note**: Replace `uXXXX` with your actual Hostinger username (visible on your hPanel dashboard under **Account Details** or the top navigation bar).

---

## Phase 1: Local Preparation & Push to GitHub

### 1.1 Compile Production Assets Locally
Shared hosting typically does not include Node.js/NPM. Compile Vite assets locally so they are tracked by Git:
```bash
npm run build
```
Verify that `public/build/manifest.json` and `public/build/assets/` exist.

### 1.2 Verify `.gitignore`
Make sure `.env`, `/vendor`, and `storage/*.key` are ignored, while `public/build` is committed:
```bash
git status
```

### 1.3 Push Code to GitHub
If you haven't pushed your repository to GitHub yet:
```bash
# Initialize git if needed
git init

# Add all project files
git add .

# Commit changes
git commit -m "Prepare admissions system for production deployment"

# Link your GitHub repository (ensure it is PRIVATE for security)
git branch -M main
git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPO.git

# Push to GitHub
git push -u origin main
```

---

## Phase 2: Hostinger Initial Setup

### 2.1 Enable SSH Access in hPanel
1. Log in to **Hostinger hPanel** (`https://hpanel.hostinger.com`).
2. Go to **Websites** -> Click **Manage** next to your domain.
3. In the left sidebar, navigate to **Advanced** -> **SSH Access**.
4. Click **Enable SSH**.
5. Note down:
   - **SSH IP**
   - **Port** (usually `65002`)
   - **Username** (`uXXXX`)
   - **Password** (same as your primary hosting account password)

### 2.2 Configure PHP Version & Extensions
1. In hPanel, go to **Advanced** -> **PHP Configuration**.
2. Select **PHP 8.2** or **PHP 8.3**.
3. Under **PHP Extensions**, make sure these are checked:
   - `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `curl`, `gd`, `bcmath`, `zip`, `intl`, `xml`.
4. Under **PHP Options**:
   - `upload_max_filesize`: `20M`
   - `post_max_size`: `25M`
   - `memory_limit`: `256M`
   - `max_execution_time`: `120`

### 2.3 Create MySQL Database in hPanel
1. Navigate to **Databases** -> **Management**.
2. Create database:
   - **Database Name**: e.g., `uXXXX_admsys`
   - **Username**: e.g., `uXXXX_admsysuser`
   - **Password**: Set a strong password (save this for `.env`).
   - Click **Create**.
3. Next to the new database, click **Enter phpMyAdmin**.
4. If you have an existing local database dump (`.sql`), click **Import** -> select your file -> click **Go**.
   *(Otherwise, you can run `php artisan migrate` in Step 3.4)*.

---

## Phase 3: Clone & Configure on Hostinger via SSH

### 3.1 Connect via SSH
Open your terminal (macOS/Linux/PowerShell) or PuTTY (Windows) and connect:
```bash
ssh -p 65002 uXXXX@YOUR_SERVER_IP
```
Enter your password when prompted.

### 3.2 Clone the Repository into `admsys`
Once connected, you will be in your user home directory (`/home/uXXXX/`).
Run:
```bash
# Clone directly into 'admsys' folder
git clone https://github.com/YOUR_USERNAME/YOUR_REPO.git admsys
```

> **Tip for Private Repositories**:
> If GitHub asks for credentials, generate a **GitHub Personal Access Token (Classic)** with `repo` scope from GitHub (*Settings -> Developer Settings -> Personal access tokens*) and use that as the password, OR generate an SSH key on Hostinger (`ssh-keygen -t ed25519`) and add it to your GitHub account under **Deploy Keys**.

### 3.3 Install Composer Dependencies
Navigate into `admsys` and install production packages:
```bash
cd /home/uXXXX/admsys
composer install --no-dev --optimize-autoloader
```

### 3.4 Create & Configure `.env`
Copy the example environment file or create a fresh `.env`:
```bash
cp .env.example .env
nano .env
```
*(Or create/edit `.env` using Hostinger hPanel File Manager in `/home/uXXXX/admsys/.env`)*.

Set your production values:
```ini
APP_NAME="YASEEN ENGLISH SCHOOL"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://yourdomain.com

LOG_CHANNEL=daily
LOG_LEVEL=error

# Database Configuration (From Step 2.3)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=uXXXX_admsys
DB_USERNAME=uXXXX_admsysuser
DB_PASSWORD=YourDatabasePasswordHere

# Sessions, Cache & Filesystem
BROADCAST_CONNECTION=log
FILESYSTEM_DISK=public
QUEUE_CONNECTION=sync
CACHE_STORE=file
SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_DOMAIN=null

# Hostinger SMTP Email Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_USERNAME=admissions@yourdomain.com
MAIL_PASSWORD=YourEmailPasswordHere
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="admissions@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"

# Institute & Alerts
ADMIN_ALERT_EMAIL="admissions@yourdomain.com"
SCHOOL_NAME="YASEEN ENGLISH SCHOOL"
```

Save and exit `nano` (`CTRL+O`, `Enter`, then `CTRL+X`).

Generate the Laravel application key:
```bash
php artisan key:generate
```

Run database migrations if you did not import via phpMyAdmin:
```bash
php artisan migrate --force
```

---

## Phase 4: Link Public Files to `public_html`

Now configure the web server root so requests are served securely from `public_html`.

### 4.1 Sync Public Assets to `public_html`
In your SSH session:
```bash
# 1. Clear out default placeholder files in public_html
rm -rf /home/uXXXX/public_html/*

# 2. Copy compiled assets, css, images, favicon and htaccess from admsys to public_html
cp -r /home/uXXXX/admsys/public/* /home/uXXXX/public_html/
cp /home/uXXXX/admsys/public/.htaccess /home/uXXXX/public_html/
```

### 4.2 Create Storage Symlink
Create the public symlink pointing to the core storage:
```bash
# Remove existing storage folder in public_html if any
rm -rf /home/uXXXX/public_html/storage

# Create symlink from public_html/storage to admsys/storage/app/public
ln -s /home/uXXXX/admsys/storage/app/public /home/uXXXX/public_html/storage
```

### 4.3 Update `public_html/index.php`
Edit `/home/uXXXX/public_html/index.php` (via `nano /home/uXXXX/public_html/index.php` or hPanel File Manager) so it points to the `admsys` directory:

```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// 1. Maintenance mode check pointing to admsys
if (file_exists($maintenance = __DIR__.'/../admsys/storage/framework/maintenance.php')) {
    require $maintenance;
}

// 2. Register Composer autoloader from admsys
require __DIR__.'/../admsys/vendor/autoload.php';

// 3. Bootstrap Laravel application from admsys
/** @var Application $app */
$app = require_once __DIR__.'/../admsys/bootstrap/app.php';

// 4. CRITICAL: Bind public path to public_html for asset() and uploads
$app->usePublicPath(__DIR__);

// 5. Handle the incoming HTTP request
$app->handleRequest(Request::capture());
```

### 4.4 Set Permissions
Set write permissions for Laravel storage and cache directories:
```bash
cd /home/uXXXX/admsys
chmod -R 775 storage bootstrap/cache
chmod 640 .env
```

---

## Phase 5: Cache & Optimize for Production

Run the optimization commands in SSH:
```bash
cd /home/uXXXX/admsys
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Phase 6: Automated Update Script (`deploy.sh`)

To make future updates effortless, create a one-command deployment script inside `/home/uXXXX/admsys/deploy.sh`:

```bash
nano /home/uXXXX/admsys/deploy.sh
```

Paste the following script:
```bash
#!/bin/bash
set -e

echo "=== Starting Admissions System Deployment ==="

cd /home/uXXXX/admsys

# 1. Pull latest code from GitHub
echo "Pulling latest changes from Git..."
git pull origin main

# 2. Install/update Composer dependencies
echo "Updating composer packages..."
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# 3. Run any new database migrations
echo "Running migrations..."
php artisan migrate --force

# 4. Sync public files to public_html (preserving custom index.php)
echo "Syncing public assets to public_html..."
cp -r public/build ../public_html/
cp -r public/css ../public_html/ 2>/dev/null || true
cp -r public/images ../public_html/ 2>/dev/null || true
cp public/.htaccess ../public_html/.htaccess 2>/dev/null || true

# 5. Re-optimize caches
echo "Optimizing application cache..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Ensure permissions
chmod -R 775 storage bootstrap/cache

echo "=== Deployment Successfully Completed! ==="
```

Make it executable:
```bash
chmod +x /home/uXXXX/admsys/deploy.sh
```

### How to Deploy Updates in the Future:
Whenever you make changes locally:
1. Build assets and push to GitHub:
   ```bash
   npm run build
   git add .
   git commit -m "Update features"
   git push origin main
   ```
2. Log into Hostinger SSH and run:
   ```bash
   cd /home/uXXXX/admsys && ./deploy.sh
   ```
Everything is automatically pulled, compiled assets synced, migrations run, and caches refreshed in seconds!

---

## Phase 7: Hostinger Cron Job Setup

For automated applicant receipts, status updates, and cleanup:

1. In hPanel, go to **Advanced** -> **Cron Jobs**.
2. Select **Custom** type.
3. Set schedule: `* * * * *` (Every minute).
4. Command:
   ```bash
   cd /home/uXXXX/admsys && /usr/bin/php artisan schedule:run >> /dev/null 2>&1
   ```
5. Click **Save**.

---

## Verification & Troubleshooting

### Testing Checklist
- [ ] Visit `https://yourdomain.com` -> Homepage loads with styles and branding.
- [ ] Visit `https://yourdomain.com/admission` -> Portal selection page.
- [ ] Submit a test application on Form 1, Form 2, and Form 3.
- [ ] Verify that student photos/receipts load under `https://yourdomain.com/storage/...`.
- [ ] Log in at `https://yourdomain.com/admin/login`.
- [ ] Test dynamic classes and subject groups under `/admin/settings`.

### Common Fixes
1. **`500 Server Error`**:
   - Check error logs: `cat /home/uXXXX/admsys/storage/logs/laravel.log`
   - Run `php artisan config:clear` and verify permissions `chmod -R 775 storage bootstrap/cache`.
2. **`Vite manifest not found`**:
   - Confirm `public_html/build` exists and contains `manifest.json`.
   - Ensure `$app->usePublicPath(__DIR__);` is in `/home/uXXXX/public_html/index.php`.
3. **`419 Page Expired`**:
   - Check that `APP_URL` in `.env` is exact (`https://yourdomain.com`).
   - Run `php artisan config:clear`.

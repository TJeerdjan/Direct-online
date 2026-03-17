# Direct-Online Dashboard — Hostinger Deployment Guide

## Prerequisites
- Hostinger Business Web Hosting with SSH access
- Subdomain `app.direct-online.nl` pointed to your hosting
- PHP 8.2+ enabled in hPanel (PHP Configuration)
- MySQL database `u455592622_Direct_online` already exists

---

## Step 1: Prepare PHP Settings in hPanel

1. Go to **hPanel → Advanced → PHP Configuration**
2. Set PHP version to **8.2** or higher
3. Under **PHP Options**, make sure these extensions are enabled:
   - `pdo_mysql`
   - `mbstring`
   - `openssl`
   - `tokenizer`
   - `xml`
   - `ctype`
   - `json`
   - `bcmath`
   - `fileinfo`
4. Under **PHP Options**, set:
   - `upload_max_filesize` = 10M
   - `post_max_size` = 12M
   - `max_execution_time` = 120
5. Click **Save**

---

## Step 2: Set Up the Subdomain

1. Go to **hPanel → Domains → Subdomains**
2. Create subdomain: `app.direct-online.nl`
3. Note the document root (e.g., `/home/u455592622/domains/app.direct-online.nl/public_html`)
4. Enable **SSL** for the subdomain via **hPanel → Security → SSL**

---

## Step 3: Upload the Laravel App

### Option A: Via SSH (Recommended)

1. SSH into your Hostinger account:
   ```
   ssh u455592622@your-server-ip -p 65002
   ```

2. Navigate to the subdomain folder:
   ```
   cd ~/domains/app.direct-online.nl
   ```

3. Remove the default public_html contents:
   ```
   rm -rf public_html/*
   ```

4. Install Composer (if not available):
   ```
   cd ~
   curl -sS https://getcomposer.org/installer | php
   mv composer.phar ~/composer
   ```

5. Create the Laravel project:
   ```
   cd ~/domains/app.direct-online.nl
   ~/composer create-project laravel/laravel . --prefer-dist
   ```

6. Or upload your project files via SFTP/File Manager, then run:
   ```
   cd ~/domains/app.direct-online.nl
   ~/composer install --no-dev --optimize-autoloader
   ```

### Option B: Via File Manager

1. Download the `/app/laravel-app` folder from this environment
2. Zip the entire folder
3. Go to **hPanel → Files → File Manager**
4. Navigate to `/domains/app.direct-online.nl/`
5. Upload and extract the zip
6. Make sure the Laravel files are in the root (not in a subfolder)

---

## Step 4: Configure the Document Root

**This is the most important step.** Hostinger serves files from `public_html`, but Laravel's entry point is the `public/` folder.

### Method: Create .htaccess redirect

1. In the **root** of your subdomain folder (`~/domains/app.direct-online.nl/`), create a file called `.htaccess`:
   ```apache
   <IfModule mod_rewrite.c>
       RewriteEngine On
       RewriteRule ^(.*)$ public/$1 [L]
   </IfModule>
   ```

2. Make sure the Laravel `public/.htaccess` file exists (it should already):
   ```apache
   <IfModule mod_rewrite.c>
       <IfModule mod_negotiation.c>
           Options -MultiViews -Indexes
       </IfModule>

       RewriteEngine On

       # Handle Authorization Header
       RewriteCond %{HTTP:Authorization} .
       RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

       # Redirect Trailing Slashes If Not A Folder...
       RewriteCond %{REQUEST_FILENAME} !-d
       RewriteCond %{REQUEST_URI} (.+)/$
       RewriteRule ^ %1 [L,R=301]

       # Send Requests To Front Controller...
       RewriteCond %{REQUEST_FILENAME} !-d
       RewriteCond %{REQUEST_FILENAME} !-f
       RewriteRule ^ index.php [L]
   </IfModule>
   ```

**Alternative (if hPanel allows):** Change the document root of the subdomain to point directly to the `public/` folder.

---

## Step 5: Set Up the Environment

1. Copy the production .env file:
   ```
   cp .env.production .env
   ```
   
   Or manually create `.env` in the Laravel root with the contents from `.env.production`.

2. **Generate a new app key** (important for security):
   ```
   php artisan key:generate
   ```

3. Set proper permissions:
   ```
   chmod -R 775 storage bootstrap/cache
   ```

---

## Step 6: Run Migrations

Since your database already has the tables from the earlier migration script, you have two options:

### Option A: Skip migrations (tables already exist)
If you already ran the SQL migration script earlier, just create the sessions table:
```sql
-- Run this in phpMyAdmin:
CREATE TABLE IF NOT EXISTS sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload LONGTEXT NOT NULL,
    last_activity INT NOT NULL,
    INDEX sessions_user_id_index (user_id),
    INDEX sessions_last_activity_index (last_activity)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### Option B: Run Laravel migrations (clean start)
If you want Laravel to manage the schema:
```
php artisan migrate --force
```
**Warning:** This will try to create tables that may already exist. To avoid errors, you can run:
```
php artisan migrate:status
```
And then selectively run what's missing.

---

## Step 7: Create the Agency Admin Account

Run this in your Hostinger SSH or via phpMyAdmin:

### Via SSH:
```
php artisan tinker
```
Then paste:
```php
\App\Models\AgencyUser::create([
    'naam' => 'Admin',
    'email' => 'admin@direct-online.nl',
    'password' => bcrypt('YOUR_SECURE_PASSWORD_HERE'),
    'role' => 'super_admin',
]);
```

### Via phpMyAdmin:
```sql
INSERT INTO agency_users (naam, email, password, role, created_at, updated_at)
VALUES (
    'Admin',
    'admin@direct-online.nl',
    '$2y$12$YOUR_BCRYPT_HASH_HERE',
    'super_admin',
    NOW(),
    NOW()
);
```
To generate the bcrypt hash, use: https://bcrypt-generator.com/

---

## Step 8: Create Storage Symlink

```
php artisan storage:link
```
This creates a symlink from `public/storage` to `storage/app/public` so uploaded images are accessible.

If symlinks don't work on shared hosting, create a route-based file serving approach or upload directly to `public/uploads/`.

---

## Step 9: Optimize for Production

```
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Step 10: Test!

1. Visit `https://app.direct-online.nl`
2. You should see the login page
3. Log in with your agency admin credentials
4. Create your first real client

---

## Troubleshooting

### "500 Internal Server Error"
- Check `storage/logs/laravel.log` for details
- Make sure `storage/` and `bootstrap/cache/` are writable: `chmod -R 775 storage bootstrap/cache`
- Make sure `.env` file exists and has correct DB credentials

### "Page not found" on routes
- Make sure the `.htaccess` files are in place (root + public/)
- Make sure `mod_rewrite` is enabled in PHP config

### "SQLSTATE Connection refused"
- Verify DB credentials in `.env`
- On Hostinger, `DB_HOST` should be `localhost` (not 127.0.0.1)

### Images not showing after upload
- Run `php artisan storage:link`
- If that doesn't work, check if symlinks are supported on your hosting plan

### Session issues / getting logged out
- Make sure `SESSION_DOMAIN=app.direct-online.nl` is set in `.env`
- Make sure `storage/framework/sessions/` is writable

---

## File Structure on Hostinger
```
~/domains/app.direct-online.nl/
├── .env                    ← Production environment config
├── .htaccess               ← Redirects to public/
├── app/                    ← Application code
├── bootstrap/              ← Framework bootstrap
├── config/                 ← Configuration files
├── database/               ← Migrations & seeders
├── public/                 ← Web entry point (index.php)
│   ├── .htaccess           ← Laravel URL rewriting
│   ├── index.php           ← Front controller
│   └── storage → ../storage/app/public
├── resources/views/        ← Blade templates
├── routes/                 ← Route definitions
├── storage/                ← Logs, cache, uploads
│   ├── app/public/         ← Uploaded files
│   ├── framework/          ← Cache, sessions, views
│   └── logs/               ← laravel.log
└── vendor/                 ← Composer dependencies
```

---

## Quick Reference Commands

| Task | Command |
|------|---------|
| Clear all cache | `php artisan optimize:clear` |
| Rebuild cache | `php artisan optimize` |
| Check routes | `php artisan route:list` |
| Run migrations | `php artisan migrate --force` |
| Create admin | `php artisan tinker` |
| View logs | `tail -f storage/logs/laravel.log` |
| Maintenance mode ON | `php artisan down` |
| Maintenance mode OFF | `php artisan up` |

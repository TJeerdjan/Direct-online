# Upgrade Guide: Webshop & Media Library (v2)

This guide walks you through deploying the new **Products (Webshop)** and **Media Library** features on your existing Hostinger installation.

**Estimated time:** 15-20 minutes
**Risk level:** Low (only adds new features, no changes to existing data)

---

## Overview of Changes

| What | Description |
|------|-------------|
| New table | `products` — stores product catalog per client |
| Updated table | `media_DO` — may need new columns if using old schema |
| New files | 2 Controllers, 1 Model, 4 Views |
| Updated files | Routes, Sidebar layout, api.php |

---

## Step 1: Database — Create the `products` Table

Run this SQL in **phpMyAdmin** (Hostinger → hPanel → Databases → phpMyAdmin):

```sql
CREATE TABLE IF NOT EXISTS `products` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `client_id` BIGINT UNSIGNED NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL,
    `description` TEXT NULL,
    `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `currency` VARCHAR(3) NOT NULL DEFAULT 'EUR',
    `category` VARCHAR(100) NULL,
    `brand` VARCHAR(100) NULL,
    `sku` VARCHAR(100) NULL,
    `stock_quantity` INT NULL,
    `weight` DECIMAL(8,2) NULL,
    `variants` JSON NULL,
    `image_id` BIGINT UNSIGNED NULL,
    `is_available` TINYINT(1) NOT NULL DEFAULT 1,
    `is_visible` TINYINT(1) NOT NULL DEFAULT 1,
    `sort_order` INT NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    FOREIGN KEY (`client_id`) REFERENCES `klanten_DO`(`id`) ON DELETE CASCADE,
    INDEX `products_client_id_is_visible_index` (`client_id`, `is_visible`),
    INDEX `products_category_index` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## Step 2: Database — Verify/Update the `media_DO` Table

The Media Library needs specific columns. Check your existing `media_DO` table and compare.

### Option A: If `media_DO` does NOT exist yet, create it:

```sql
CREATE TABLE IF NOT EXISTS `media_DO` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `client_id` BIGINT UNSIGNED NOT NULL,
    `file_name` VARCHAR(255) NOT NULL,
    `file_path` VARCHAR(500) NOT NULL,
    `alt_text` VARCHAR(255) NULL,
    `media_type` ENUM('image','video','document') DEFAULT 'image',
    `mime_type` VARCHAR(100) NULL,
    `file_size` INT NULL,
    `usage_key` VARCHAR(100) NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `uploaded_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL,
    FOREIGN KEY (`client_id`) REFERENCES `klanten_DO`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### Option B: If `media_DO` already exists with old Dutch columns (`bestandsnaam`, `pad`, `type`):

Run these ALTER statements to add the missing columns:

```sql
-- Rename old columns (if they exist)
ALTER TABLE `media_DO` CHANGE COLUMN `bestandsnaam` `file_name` VARCHAR(255) NOT NULL;
ALTER TABLE `media_DO` CHANGE COLUMN `pad` `file_path` VARCHAR(500) NOT NULL;
ALTER TABLE `media_DO` CHANGE COLUMN `type` `media_type` ENUM('image','video','document') DEFAULT 'image';

-- Add new columns that didn't exist before
ALTER TABLE `media_DO` ADD COLUMN `alt_text` VARCHAR(255) NULL AFTER `file_path`;
ALTER TABLE `media_DO` ADD COLUMN `mime_type` VARCHAR(100) NULL AFTER `media_type`;
ALTER TABLE `media_DO` ADD COLUMN `file_size` INT NULL AFTER `mime_type`;
ALTER TABLE `media_DO` ADD COLUMN `usage_key` VARCHAR(100) NULL AFTER `file_size`;
ALTER TABLE `media_DO` ADD COLUMN `is_active` TINYINT(1) DEFAULT 1 AFTER `usage_key`;
ALTER TABLE `media_DO` ADD COLUMN `uploaded_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER `is_active`;
ALTER TABLE `media_DO` ADD COLUMN `updated_at` TIMESTAMP NULL AFTER `uploaded_at`;
```

> **Note:** If some columns already exist, you'll get a harmless "Duplicate column name" error. Just skip those.

### Option C: If `media_DO` exists with the correct English column names already:

No action needed. Just verify these columns exist: `id`, `client_id`, `file_name`, `file_path`, `alt_text`, `media_type`, `mime_type`, `file_size`, `usage_key`, `is_active`, `uploaded_at`, `updated_at`.

---

## Step 3: Create the Uploads Directory

Via SSH or File Manager, create the uploads folder and set permissions:

```bash
cd ~/domains/app.direct-online.nl
mkdir -p public/uploads
chmod 775 public/uploads
```

This is where client images will be stored (in subfolders per client: `public/uploads/{client_id}/`).

---

## Step 4: Upload New Files

Upload these files from the download to your Hostinger installation. The paths below are relative to your Laravel root (`~/domains/app.direct-online.nl/`).

### New Files to Upload (create these):

| Local Path | Upload To |
|------------|-----------|
| `app/Models/Product.php` | `app/Models/Product.php` |
| `app/Models/Media.php` | `app/Models/Media.php` |
| `app/Http/Controllers/Client/ProductController.php` | `app/Http/Controllers/Client/ProductController.php` |
| `app/Http/Controllers/Client/MediaController.php` | `app/Http/Controllers/Client/MediaController.php` |
| `resources/views/client/products/index.blade.php` | `resources/views/client/products/index.blade.php` |
| `resources/views/client/products/create.blade.php` | `resources/views/client/products/create.blade.php` |
| `resources/views/client/products/edit.blade.php` | `resources/views/client/products/edit.blade.php` |
| `resources/views/client/media/index.blade.php` | `resources/views/client/media/index.blade.php` |

> **Create the directories first** if they don't exist:
> - `resources/views/client/products/`
> - `resources/views/client/media/`

### Existing Files to Replace (overwrite these):

| Local Path | Upload To | What Changed |
|------------|-----------|-------------|
| `routes/web.php` | `routes/web.php` | Added Media + Product routes |
| `resources/views/layouts/app.blade.php` | `resources/views/layouts/app.blade.php` | Added Producten + Media sidebar links |

---

## Step 5: Update `api.php` on Client Websites

The `api.php` file now supports a `products` endpoint. Upload the updated `api.php` to each client website that needs product data.

New usage:
```
api.php?key=API_KEY&type=products    → products only
api.php?key=API_KEY                  → all data (now includes products)
```

The updated file is in: `/php-fix/api.php` (from the download)

---

## Step 6: Clear Laravel Cache

After uploading all files, run via SSH:

```bash
cd ~/domains/app.direct-online.nl
php artisan optimize:clear
php artisan optimize
```

Or if you don't have SSH, you can clear the cache by deleting these directories and letting Laravel recreate them:
- `bootstrap/cache/` (delete all files EXCEPT `.gitignore`)
- `storage/framework/cache/data/` (delete contents)
- `storage/framework/views/` (delete contents)

---

## Step 7: Test

1. Go to `https://app.direct-online.nl` and log in
2. Navigate to **Producten** in the sidebar → should show empty state
3. Click **Nieuw product** → fill in the form → save
4. Navigate to **Media** in the sidebar → upload a test image
5. Go back to product creation → your media library images should appear for selection
6. Test the API: `https://clientsite.nl/api.php?key=API_KEY&type=products`

---

## File Upload Checklist

Use this checklist while uploading:

- [ ] SQL: `products` table created
- [ ] SQL: `media_DO` table verified/updated
- [ ] Directory: `public/uploads/` created with 775 permissions
- [ ] File: `app/Models/Product.php` — NEW
- [ ] File: `app/Models/Media.php` — NEW
- [ ] File: `app/Http/Controllers/Client/ProductController.php` — NEW
- [ ] File: `app/Http/Controllers/Client/MediaController.php` — NEW
- [ ] File: `resources/views/client/products/index.blade.php` — NEW
- [ ] File: `resources/views/client/products/create.blade.php` — NEW
- [ ] File: `resources/views/client/products/edit.blade.php` — NEW
- [ ] File: `resources/views/client/media/index.blade.php` — NEW
- [ ] File: `routes/web.php` — REPLACE
- [ ] File: `resources/views/layouts/app.blade.php` — REPLACE
- [ ] File: Client sites `api.php` — REPLACE (optional, only if client needs products)
- [ ] Cache: Ran `php artisan optimize:clear && php artisan optimize`
- [ ] Test: Products page loads
- [ ] Test: Media upload works
- [ ] Test: Product creation with image works

---

## Troubleshooting

### "Class App\Models\Product not found"
- Make sure `app/Models/Product.php` was uploaded correctly
- Run `php artisan optimize:clear` to clear the class cache

### Images not showing after upload
- Check that `public/uploads/` directory exists and is writable (775)
- Check that the uploaded image is in `public/uploads/{client_id}/`

### "SQLSTATE: Table doesn't exist"
- Double-check that both `products` and `media_DO` tables were created in phpMyAdmin
- Verify you're in the correct database (`u455592622_Direct_online`)

### 500 error on Products page
- Check `storage/logs/laravel.log` for the specific error
- Most likely a missing column in `media_DO` — run the ALTER statements from Step 2

### Media picker shows no images in product form
- Upload at least one image via the Media page first
- The picker only shows images (not videos or PDFs)

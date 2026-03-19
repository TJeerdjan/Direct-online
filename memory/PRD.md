# Direct-Online Dashboard PRD

## Project Overview
Multi-tenant CMS dashboard for Direct-Online agency (direct-online.nl). Allows the agency to manage client websites through a branded admin panel, and clients to manage their own content (portfolio, testimonials, inbox, settings). Client websites pull data via a public PHP API.

## Architecture

### Tech Stack
- **Framework:** Laravel 11 (PHP 8.2+)
- **Database:** MySQL 8.3 on Hostinger (`u455592622_Direct_online`)
- **Frontend:** Blade templates + TailwindCSS CDN + Alpine.js
- **Auth:** Session-based with custom middleware, supports bcrypt/MD5/plain text password migration
- **Hosting:** Hostinger Business Web Hosting at `app.direct-online.nl`
- **Client Sites:** Static/JS sites with shared PHP API files (`api.php`, `submit-form.php`, `db.php`)

### Multi-Tenant Design
Single MySQL database, `client_id` column on every tenant-scoped table.

### Database Schema (Production)
| Table | Purpose | Key Columns |
|-------|---------|-------------|
| `klanten_DO` | Clients | naam, slug, domain, plan, status, api_key |
| `users_DO` | Client users | client_id, email, naam, password_hash, role |
| `agency_users` | Agency admins | name, email, password_hash, role |
| `projects_DO` | Portfolio | client_id, title, description, category, is_visible, sort_order |
| `testimonials` | Testimonials | client_id, client_name, client_company, quote, rating, is_visible |
| `formulieren_DO` | Form submissions | client_id, naam, email, bericht, status, is_gelezen |
| `client_settings` | Site settings | client_id, site_name, tagline, primary_color, accent_color, socials |
| `media_DO` | File uploads | client_id, bestandsnaam, pad, type |
| `sessions` | Laravel sessions | id, user_id, payload |

## What's Been Built

### Dashboard (Laravel) — Deployed on Hostinger
- [x] Login page with Direct-Online branding
- [x] Agency admin login + client login (supports legacy password formats with auto-rehash to bcrypt)
- [x] Agency dashboard with stats (total clients, active, new messages)
- [x] Client management: create, edit, view all clients with plan/status badges
- [x] Client impersonation: agency views client dashboard as the client, with banner + exit button
- [x] Client dashboard with stats, recent messages, quick action links
- [x] Portfolio CRUD (create, edit, delete, visibility toggle)
- [x] Testimonials CRUD (name, company, title, quote, rating, visibility)
- [x] Inbox: view form submissions, filter by status (nieuw/in behandeling/afgehandeld), archive
- [x] Message detail: read message, change status, archive
- [x] Site Settings: website name, tagline, colors (picker), social media links
- [x] Dutch language UI throughout
- [x] Responsive sidebar layout with Direct-Online branding (#1c2336, #129387, #f59d0e)

### Client Website Files (PHP) — Deployed to all client sites
- [x] `db.php` — shared database connection
- [x] `submit-form.php` — universal contact form handler (fixed `klant_id` → `client_id`, typo fix, removed old db.php references)
- [x] `api.php` — public data API (portfolio, testimonials, settings) with api_key auth

### Deployment
- [x] Production `.env` configured for Hostinger MySQL
- [x] Step-by-step deployment guide (`DEPLOYMENT.md`)
- [x] Root `.htaccess` for subdomain routing

## Test Credentials
| Role | Email | Password |
|------|-------|----------|
| Agency Admin | admin@direct-online.nl | admin123 |
| Demo Client | jan@demo-fotograaf.nl | demo123 |

## Key Files
- **Laravel app:** `/app/laravel-app/`
- **Client PHP files:** `/app/php-fix/` (submit-form.php, api.php, db.php)
- **Routes:** `/app/laravel-app/routes/web.php`
- **Controllers:** `/app/laravel-app/app/Http/Controllers/`
- **Models:** `/app/laravel-app/app/Models/`
- **Views:** `/app/laravel-app/resources/views/`
- **Migrations:** `/app/laravel-app/database/migrations/`

## Last Updated
March 18, 2026

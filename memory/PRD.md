# Direct-Online Dashboard PRD

## Project Overview
Multi-tenant CMS dashboard for Direct-Online agency (direct-online.nl) to manage client websites. Allows freelancers and businesses to manage their portfolio websites through a branded dashboard.

## Original Problem Statement
Build a standard backend/dashboard that Direct-Online can use for all clients, where clients can manage their website content, with optional GoHighLevel (GHL) integration for advanced CRM features.

## Architecture

### Tech Stack (Current - Laravel)
- **Framework:** Laravel 11 (PHP 8.2)
- **Database:** SQLite (preview) / MySQL 8.3 (production on Hostinger)
- **Frontend:** Blade templates + TailwindCSS CDN + Alpine.js
- **Auth:** Session-based with custom middleware
- **Hosting:** Hostinger Business Web Hosting (subdomain: app.direct-online.nl)

### Multi-Tenant Design
Single database with `client_id` column on each tenant-scoped table.

### Database Schema
- `klanten_DO` - Clients (id, naam, slug, domain, plan, status, api_key, modules)
- `users_DO` - Client users (id, client_id, naam, email, password, role, taal)
- `agency_users` - Agency admin users (id, naam, email, password, role)
- `projects_DO` - Portfolio items (id, client_id, titel, slug, beschrijving, categorie, afbeelding, url, volgorde, is_actief)
- `testimonials` - Client testimonials (id, client_id, naam, bedrijf, tekst, score, is_actief, volgorde)
- `formulieren_DO` - Form submissions/inbox (id, client_id, naam, email, telefoon, bericht, status, is_gelezen, is_gearchiveerd)
- `client_settings` - Site settings (id, client_id, site_naam, primaire_kleur, secundaire_kleur, over_tekst, social links)
- `media_DO` - File uploads (id, client_id, bestandsnaam, pad, type, grootte)
- `sessions` - Laravel session storage

## User Personas

### Agency Admin (Direct-Online)
- Manages all clients/tenants
- Creates client accounts with user credentials
- Views dashboard stats (clients, messages)
- Can impersonate clients to view their dashboard

### Client (Freelancers/Businesses)
- Manages their website content
- Portfolio, testimonials management
- Receives and manages form submissions (inbox)
- Configures site settings (colors, social, contact)

## Implemented Features

### MVP - Phase 1 (March 17, 2026)
- [x] Login/logout with session-based auth
- [x] Role-based access (agency admin, client)
- [x] Agency dashboard with stats
- [x] Client management (CRUD) from agency panel
- [x] Client impersonation (view as client)
- [x] Client dashboard with stats + quick links
- [x] Portfolio management (CRUD with image upload)
- [x] Testimonials management (CRUD with star ratings)
- [x] Inbox (form submissions with status filters)
- [x] Message detail view with status change + archive
- [x] Site settings (colors, social media, contact info)
- [x] Dutch language UI throughout
- [x] Direct-Online branding (dark theme: #1c2336, accent: #129387, CTA: #f59d0e)
- [x] HTTPS proxy trust for Kubernetes/production
- [x] Responsive sidebar layout

## Business Model

### Setup Fees (One-Time)
| Client Type | Price | Delivery |
|-------------|-------|----------|
| Freelancer Portfolio | EUR495 | 24h |
| Blog Website | EUR595 | 24-48h |
| Business Website | EUR795 | 48-72h |
| Webshop | EUR1,295 | 3-5 days |

### Monthly Plans
| Plan | Price | Features |
|------|-------|----------|
| Early Bird | EUR10/mo | Limited 10 clients, 1.5yr |
| Starter | EUR29/mo | Freelancers |
| Growth | EUR59/mo | + Blog |
| Webshop | EUR99/mo | + E-commerce |

## Test Credentials

**Agency Admin:**
- Email: admin@direct-online.nl
- Password: admin123

**Demo Client 1:**
- Email: jan@demo-fotograaf.nl
- Password: demo123

**Demo Client 2:**
- Email: pieter@gouden-aar.nl
- Password: demo123

## Prioritized Backlog

### P0 - Contact Form Fix (UNRESOLVED)
- [ ] Contact form on live client sites broken after migration (`klant_id` -> `client_id`)
- Fix provided but user reports still not working. Needs server-side error log debugging.

### P1 - Phase 2 (Next)
- [ ] Blog module (Pages CRUD)
- [ ] Email notifications on new form submissions
- [ ] Media library with upload management
- [ ] Client website frontend templates
- [ ] Public API endpoints for client websites

### P2 - Feature & Plan Management
- [ ] Module system (enable/disable features per plan)
- [ ] Billing & subscription tracking
- [ ] Multilingual support (Dutch/English toggle)

### P3 - Integrations & E-commerce
- [ ] GoHighLevel (GHL) CRM sync
- [ ] Stripe/Mollie payment integration
- [ ] Webshop features (Products, Orders, Payments)

## Key Files
- Routes: `/app/laravel-app/routes/web.php`
- Controllers: `/app/laravel-app/app/Http/Controllers/`
- Models: `/app/laravel-app/app/Models/`
- Views: `/app/laravel-app/resources/views/`
- Migrations: `/app/laravel-app/database/migrations/`
- Seeder: `/app/laravel-app/database/seeders/DemoSeeder.php`

## Last Updated
March 17, 2026 - Laravel MVP Phase 1 Complete

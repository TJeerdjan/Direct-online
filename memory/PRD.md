# Direct-Online Dashboard PRD

## Project Overview
Multi-tenant CMS dashboard for Direct-Online agency (direct-online.nl) to manage client websites. Allows freelancers and businesses to manage their portfolio websites through a branded dashboard.

## Original Problem Statement
Build a standard backend/dashboard that Direct-Online can use for all clients, where clients can manage their website content, with optional GoHighLevel (GHL) integration for advanced CRM features.

## Architecture

### Multi-Tenant Setup
```
agency_master_db (Direct-Online)
├── tenants (client registry)
├── users (all users - agency + clients)
├── plans (subscription plans)
├── feedback (all client feedback)
└── subscriptions (billing info)

client_{slug}_db (Per Client)
├── portfolio
├── testimonials
├── pages
├── form_submissions
├── media
└── settings
```

### Tech Stack
- **Frontend**: React 19, TailwindCSS, shadcn/ui
- **Backend**: FastAPI (Python)
- **Database**: MongoDB (motor async driver)
- **Auth**: JWT tokens
- **i18n**: Dutch (primary), English

## User Personas

### Agency Admin (Direct-Online)
- Manages all clients/tenants
- Creates client accounts
- Views all feedback
- Configures plans and modules

### Client (Freelancers/Businesses)
- Manages their website content
- Portfolio, testimonials, pages
- Receives form submissions
- Provides feedback

## Core Requirements

### ✅ Implemented (MVP - Feb 2026)

**Authentication**
- [x] Login/logout with JWT
- [x] Role-based access (agency_admin, client)
- [x] Language preference per user

**Client Dashboard**
- [x] Overview with stats
- [x] Portfolio management (CRUD)
- [x] Testimonials management (CRUD)
- [x] Pages management (CRUD)
- [x] Inbox (form submissions)
- [x] Feedback submission
- [x] Settings (site name, colors, social links)

**Agency Admin Dashboard**
- [x] Client/tenant management
- [x] Create users for tenants
- [x] View all feedback

**Branding & UX**
- [x] Direct-Online dark theme
- [x] Logo integration
- [x] Dutch/English translations
- [x] Mobile-responsive sidebar

## Business Model

### Setup Fees (One-Time)
| Client Type | Price | Delivery |
|-------------|-------|----------|
| Freelancer Portfolio | €495 | 24h |
| Blog Website | €595 | 24-48h |
| Business Website | €795 | 48-72h |
| Webshop | €1,295 | 3-5 days |

### Monthly Plans
| Plan | Price | Features |
|------|-------|----------|
| Early Bird | €10/mo | Limited 10 clients, 1.5yr |
| Starter | €29/mo | Freelancers |
| Growth | €59/mo | + Blog |
| Webshop | €99/mo | + E-commerce |

### Add-Ons
- Blog Module: +€9/mo
- Managed Content: +€49/mo
- GHL Integration: +€39/mo
- Email Relay: +€5/mo

## Prioritized Backlog

### P0 - Critical (Next Sprint)
- [ ] Media library with upload functionality
- [ ] Public form submission endpoint testing
- [ ] GHL integration foundation

### P1 - Important
- [ ] Blog module (for Growth/Webshop plans)
- [ ] Email notifications (form submissions)
- [ ] Client website frontend templates
- [ ] Media storage (S3/Cloudinary)

### P2 - Nice to Have
- [ ] Advanced analytics
- [ ] Stripe payment integration
- [ ] White-label dashboard option
- [ ] Bulk import/export

### Future / Phase 2
- [ ] Full GHL CRM sync
- [ ] Appointment booking integration
- [ ] Chat widget integration
- [ ] SMS campaigns via GHL
- [ ] Webshop features (products, orders, payments)

## Test Credentials

**Agency Admin:**
- Email: admin@direct-online.nl
- Password: admin123

**Demo Client:**
- Email: jan@demo-fotograaf.nl
- Password: demo123

## API Endpoints

### Public
- `POST /api/public/form/{tenant_slug}` - Form submissions
- `POST /api/seed` - Seed demo data

### Auth
- `POST /api/auth/login` - Login
- `GET /api/auth/me` - Current user
- `PUT /api/auth/language` - Update language

### Client Dashboard
- `GET/POST/PUT/DELETE /api/portfolio`
- `GET/POST/PUT/DELETE /api/testimonials`
- `GET/POST/PUT/DELETE /api/pages`
- `GET /api/inbox`, `PUT /api/inbox/{id}/read`
- `GET/POST /api/feedback`
- `GET/PUT /api/settings`
- `GET /api/dashboard/stats`

### Admin
- `GET/POST /api/admin/tenants`
- `GET/PUT /api/admin/tenants/{id}`
- `POST /api/admin/tenants/{id}/user`
- `GET /api/admin/feedback`

## Last Updated
February 24, 2026 - MVP Complete

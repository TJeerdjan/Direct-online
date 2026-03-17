# Direct-Online Client Website Template

A reusable template for building client websites that connect to the Direct-Online CMS dashboard.

## 🚀 Quick Start

### 1. Create Tenant in Direct-Online Dashboard
1. Login to https://form-fix-debug-1.preview.emergentagent.com
2. Go to **Klanten** → **+ Klant toevoegen**
3. Fill in client details and save
4. Create a user account for the client

### 2. Set Up Website
1. Copy this template to new project
2. Update `.env` with the client's `TENANT_SLUG`
3. Customize design and content
4. Deploy!

## 📁 File Structure

```
client_template/
├── backend/
│   ├── server.py          # API that connects to Direct-Online
│   ├── requirements.txt   # Python dependencies
│   └── .env.example       # Environment variables template
│
├── frontend/
│   └── src/
│       ├── App.js         # Main app with routing
│       ├── App.css        # Styles with design tokens
│       └── components/
│           ├── Header.js
│           ├── Footer.js
│           ├── HomePage.js
│           ├── PortfolioPage.js
│           ├── PortfolioDetail.js
│           ├── AboutPage.js
│           ├── ContactPage.js
│           └── AdminRedirect.js
│
└── README.md
```

## ⚙️ Configuration

### Backend `.env`
```env
DIRECT_ONLINE_API=https://form-fix-debug-1.preview.emergentagent.com/api
TENANT_SLUG=client-slug-here
```

### Frontend `.env`
```env
REACT_APP_BACKEND_URL=https://your-deployed-url.com
```

## 🎨 Customization

### Design Tokens (App.css)
```css
:root {
  --color-primary: #14b8a6;    /* Main brand color */
  --color-accent: #f59d0e;     /* CTA/highlights */
  --color-background: #0a0a0a; /* Dark background */
  /* ... more variables */
}
```

### Components to Customize Per Client
- `HomePage.js` - Hero text, featured sections
- `AboutPage.js` - Client's story, services list
- `ContactPage.js` - Contact details (email, phone, address)

## 📡 API Endpoints

| Endpoint | Description |
|----------|-------------|
| `GET /api/settings` | Site name, logo, colors, social links |
| `GET /api/portfolio` | All portfolio items |
| `GET /api/portfolio/:slug` | Single portfolio item |
| `GET /api/portfolio/categories` | Category list for filtering |
| `GET /api/testimonials` | Client testimonials |
| `GET /api/pages` | CMS pages |
| `POST /api/contact` | Submit contact form |

## 🔗 Data Flow

```
Client Website (Frontend)
        ↓
Website Backend (server.py)
        ↓
Direct-Online API (/api/public/{tenant})
        ↓
Client's Database (MongoDB)
```

---

# 📝 PROMPT TEMPLATE FOR NEW CLIENTS

Copy and customize this prompt when starting a new client website in Emergent:

```
Build a portfolio website for [CLIENT NAME], a [INDUSTRY/PROFESSION].

## Business Info
- Company: [Company Name]
- Industry: [e.g., Photography, Graphic Design, Architecture]
- Target audience: [e.g., Local businesses, Enterprise clients]
- Style: [e.g., Modern & minimal, Bold & creative, Professional & clean]

## Technical Setup
Connect to Direct-Online CMS backend:
- TENANT_SLUG=[client-slug]
- DIRECT_ONLINE_API=https://form-fix-debug-1.preview.emergentagent.com/api

## Required Pages
1. **Home** - Hero, featured portfolio, testimonials, CTA
2. **Portfolio** - Grid with category filter, detail pages
3. **Over Ons/About** - Story, services, team (if applicable)
4. **Contact** - Form (submits to Direct-Online), contact details

## Design Requirements
- Color scheme: [Primary color], [Accent color]
- Style: [Dark theme / Light theme]
- Typography: [Modern / Classic / etc.]
- Special features: [Animations, parallax, etc.]

## Important
- /admin route should redirect to Direct-Online dashboard
- Portfolio, testimonials, pages come from CMS API
- Contact form submits to: POST /api/public/{tenant_slug}/contact
- Site settings (name, logo, colors) from: GET /api/public/{tenant_slug}/settings
```

---

## Example Prompts

### Photographer
```
Build a portfolio website for Jan de Vries Fotografie.

## Business Info
- Company: Jan de Vries Fotografie
- Industry: Wedding & Portrait Photography
- Target: Couples, families in Netherlands
- Style: Elegant, emotional, warm

## Technical Setup
- TENANT_SLUG=jan-de-vries
- Connect to Direct-Online CMS

## Design
- Colors: Deep burgundy (#722F37), Gold accents (#D4AF37)
- Dark theme with large imagery
- Smooth scroll animations
```

### Design Agency
```
Build a portfolio website for Studio Creatief.

## Business Info
- Company: Studio Creatief
- Industry: Graphic Design & Branding
- Target: Startups, SMBs
- Style: Bold, modern, colorful

## Technical Setup
- TENANT_SLUG=studio-creatief
- Connect to Direct-Online CMS

## Design
- Colors: Electric blue (#0066FF), Coral (#FF6B6B)
- Light theme with bold typography
- Micro-interactions on hover
```

### Bakery
```
Build a website for Bakkerij Jansen.

## Business Info
- Company: Bakkerij Jansen
- Industry: Traditional Dutch Bakery
- Target: Local customers
- Style: Warm, inviting, traditional

## Technical Setup
- TENANT_SLUG=bakkerij-jansen
- Connect to Direct-Online CMS

## Pages
- Home with daily specials
- Portfolio (their products/gallery)
- About (history, team)
- Contact with opening hours

## Design
- Colors: Warm brown (#8B4513), Cream (#FFF8DC)
- Light theme
- Cozy, artisanal feel
```

---

## 🛠️ Maintenance

### Client Wants to Update Content
→ Direct them to Direct-Online dashboard

### Client Wants Design Changes
→ Modify CSS variables or components, redeploy

### Client Wants New Features
→ Add to plan/subscription, implement in website code

---

Created by Direct-Online • https://direct-online.nl

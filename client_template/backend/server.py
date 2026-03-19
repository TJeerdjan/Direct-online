# ================================================================
# DIRECT-ONLINE CLIENT WEBSITE TEMPLATE
# ================================================================
# 
# This is a reusable template for building client websites that
# connect to the Direct-Online CMS dashboard.
#
# SETUP INSTRUCTIONS:
# 1. Create tenant in Direct-Online dashboard
# 2. Copy this template to new Emergent workspace
# 3. Update .env with TENANT_SLUG
# 4. Customize design tokens in App.css
# 5. Deploy!
#
# ================================================================

from fastapi import FastAPI, APIRouter, HTTPException
from fastapi.middleware.cors import CORSMiddleware
from dotenv import load_dotenv
from pathlib import Path
import httpx
import os
import logging

# ================================================================
# CONFIGURATION
# ================================================================

ROOT_DIR = Path(__file__).parent
load_dotenv(ROOT_DIR / '.env')

# Direct-Online API - Don't change unless API moves
DIRECT_ONLINE_API = os.environ.get(
    'DIRECT_ONLINE_API', 
    'https://agency-dashboard-61.preview.emergentagent.com/api'
)

# Client's tenant slug - CHANGE THIS PER CLIENT
TENANT_SLUG = os.environ.get('TENANT_SLUG', 'demo-client')

# Logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

# ================================================================
# APP SETUP
# ================================================================

app = FastAPI(title="Client Website API")
api_router = APIRouter(prefix="/api")

app.add_middleware(
    CORSMiddleware,
    allow_credentials=True,
    allow_origins=["*"],
    allow_methods=["*"],
    allow_headers=["*"],
)

# ================================================================
# HELPER: Fetch from Direct-Online
# ================================================================

async def fetch_from_cms(endpoint: str, params: dict = None):
    """
    Fetch data from Direct-Online CMS.
    Handles errors gracefully with fallbacks.
    """
    url = f"{DIRECT_ONLINE_API}/public/{TENANT_SLUG}{endpoint}"
    try:
        async with httpx.AsyncClient() as client:
            response = await client.get(url, params=params, timeout=10.0)
            response.raise_for_status()
            return response.json()
    except httpx.HTTPStatusError as e:
        if e.response.status_code == 404:
            return None
        logger.error(f"CMS API error: {e}")
        raise HTTPException(status_code=502, detail="Could not fetch data")
    except httpx.RequestError as e:
        logger.error(f"CMS connection error: {e}")
        raise HTTPException(status_code=502, detail="CMS unavailable")

async def post_to_cms(endpoint: str, data: dict):
    """Post data to Direct-Online CMS."""
    url = f"{DIRECT_ONLINE_API}/public/{TENANT_SLUG}{endpoint}"
    try:
        async with httpx.AsyncClient() as client:
            response = await client.post(url, json=data, timeout=10.0)
            response.raise_for_status()
            return response.json()
    except httpx.HTTPError as e:
        logger.error(f"CMS POST error: {e}")
        raise HTTPException(status_code=502, detail="Could not send data")

# ================================================================
# API ROUTES
# ================================================================

@api_router.get("/")
async def root():
    return {"status": "ok", "tenant": TENANT_SLUG}

# ----- Site Settings -----
@api_router.get("/settings")
async def get_settings():
    """Get site settings (name, logo, colors, social links)"""
    data = await fetch_from_cms("/settings")
    if not data:
        return {
            "site_name": "Client Website",
            "tagline": "",
            "logo": "",
            "colors": {"primary": "#14b8a6", "accent": "#f59d0e"},
            "social": {}
        }
    return data

# ----- Portfolio -----
@api_router.get("/portfolio")
async def get_portfolio(category: str = None):
    """Get all published portfolio items"""
    params = {"category": category} if category else None
    items = await fetch_from_cms("/portfolio", params)
    
    # Transform to standard format
    return [{
        "id": item["id"],
        "title": item["title"],
        "slug": item["slug"],
        "description": item["description"],
        "image": item["images"][0] if item.get("images") else "",
        "images": item.get("images", []),
        "client": item.get("client_name", ""),
        "category": item.get("category", ""),
        "tags": item.get("tags", []),
        "year": item.get("created_at", "")[:4] if item.get("created_at") else "",
    } for item in (items or [])]

@api_router.get("/portfolio/categories")
async def get_categories():
    """Get portfolio categories for filtering"""
    data = await fetch_from_cms("/portfolio-categories")
    return data or {"categories": []}

@api_router.get("/portfolio/{slug}")
async def get_portfolio_item(slug: str):
    """Get single portfolio item by slug"""
    item = await fetch_from_cms(f"/portfolio/{slug}")
    if not item:
        raise HTTPException(status_code=404, detail="Project not found")
    
    return {
        "id": item["id"],
        "title": item["title"],
        "slug": item["slug"],
        "description": item["description"],
        "image": item["images"][0] if item.get("images") else "",
        "images": item.get("images", []),
        "client": item.get("client_name", ""),
        "category": item.get("category", ""),
        "tags": item.get("tags", []),
    }

# ----- Testimonials -----
@api_router.get("/testimonials")
async def get_testimonials(limit: int = None):
    """Get published testimonials"""
    params = {"limit": limit} if limit else None
    return await fetch_from_cms("/testimonials", params) or []

# ----- Pages -----
@api_router.get("/pages")
async def get_pages():
    """Get all published pages"""
    return await fetch_from_cms("/pages") or []

@api_router.get("/pages/{slug}")
async def get_page(slug: str):
    """Get single page by slug"""
    page = await fetch_from_cms(f"/pages/{slug}")
    if not page:
        raise HTTPException(status_code=404, detail="Page not found")
    return page

# ----- Contact Form -----
@api_router.post("/contact")
async def submit_contact(data: dict):
    """Submit contact form"""
    payload = {
        "name": data.get("name", ""),
        "email": data.get("email", ""),
        "message": data.get("message", ""),
        "source_page": data.get("source", "/contact")
    }
    await post_to_cms("/contact", payload)
    return {"success": True, "message": "Message sent successfully"}

# ----- Admin Redirect Info -----
@api_router.get("/admin-url")
async def get_admin_url():
    """Return dashboard URL for admin redirect"""
    return {
        "url": "https://agency-dashboard-61.preview.emergentagent.com",
        "tenant": TENANT_SLUG
    }

# ================================================================
# INCLUDE ROUTER
# ================================================================

app.include_router(api_router)

@app.on_event("startup")
async def startup():
    logger.info(f"🚀 Website API started for tenant: {TENANT_SLUG}")
    logger.info(f"📡 Connected to CMS: {DIRECT_ONLINE_API}")

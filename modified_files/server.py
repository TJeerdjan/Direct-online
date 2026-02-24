# =====================================================
# MODIFIED server.py for portfolio-voorbeeld.nl
# Connects to Direct-Online Dashboard API
# =====================================================

from fastapi import FastAPI, APIRouter, HTTPException
from dotenv import load_dotenv
from starlette.middleware.cors import CORSMiddleware
import os
import logging
from pathlib import Path
import httpx

ROOT_DIR = Path(__file__).parent
load_dotenv(ROOT_DIR / '.env')

# Direct-Online API Configuration
# Add this to your .env file:
# DIRECT_ONLINE_API=https://ghl-connect-2.preview.emergentagent.com/api
# TENANT_SLUG=vermeerdesign

DIRECT_ONLINE_API = os.environ.get('DIRECT_ONLINE_API', 'https://ghl-connect-2.preview.emergentagent.com/api')
TENANT_SLUG = os.environ.get('TENANT_SLUG', 'vermeerdesign')

# Create the main app
app = FastAPI(title="Vermeer Design Website")

api_router = APIRouter(prefix="/api")

# Configure logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s'
)
logger = logging.getLogger(__name__)

# ============== PROXY ROUTES TO DIRECT-ONLINE API ==============

@api_router.get("/")
async def root():
    return {"message": "Vermeer Design API - Connected to Direct-Online"}

# Get all portfolio items from Direct-Online
@api_router.get("/portfolio")
async def get_portfolio_items(category: str = None):
    """Fetch portfolio items from Direct-Online dashboard"""
    try:
        url = f"{DIRECT_ONLINE_API}/public/{TENANT_SLUG}/portfolio"
        if category:
            url += f"?category={category}"
        
        async with httpx.AsyncClient() as client:
            response = await client.get(url, timeout=10.0)
            response.raise_for_status()
            items = response.json()
            
            # Transform to match existing frontend expectations
            transformed = []
            for item in items:
                transformed.append({
                    "id": item["id"],
                    "title": item["title"],
                    "category": item["category"],
                    "description": item["description"],
                    "image": item["images"][0] if item.get("images") else "",
                    "images": item.get("images", []),
                    "client": item.get("client_name", ""),
                    "year": item.get("created_at", "")[:4] if item.get("created_at") else "",
                    "tags": item.get("tags", []),
                    "slug": item.get("slug", ""),
                })
            return transformed
            
    except httpx.HTTPError as e:
        logger.error(f"Failed to fetch portfolio from Direct-Online: {e}")
        raise HTTPException(status_code=502, detail="Could not fetch portfolio data")

# Get portfolio categories
@api_router.get("/portfolio/categories")
async def get_portfolio_categories():
    """Fetch portfolio categories from Direct-Online dashboard"""
    try:
        url = f"{DIRECT_ONLINE_API}/public/{TENANT_SLUG}/portfolio-categories"
        
        async with httpx.AsyncClient() as client:
            response = await client.get(url, timeout=10.0)
            response.raise_for_status()
            return response.json()
            
    except httpx.HTTPError as e:
        logger.error(f"Failed to fetch categories from Direct-Online: {e}")
        raise HTTPException(status_code=502, detail="Could not fetch categories")

# Get single portfolio item
@api_router.get("/portfolio/{item_slug}")
async def get_portfolio_item(item_slug: str):
    """Fetch single portfolio item from Direct-Online dashboard"""
    try:
        url = f"{DIRECT_ONLINE_API}/public/{TENANT_SLUG}/portfolio/{item_slug}"
        
        async with httpx.AsyncClient() as client:
            response = await client.get(url, timeout=10.0)
            if response.status_code == 404:
                raise HTTPException(status_code=404, detail="Portfolio item not found")
            response.raise_for_status()
            item = response.json()
            
            # Transform to match existing frontend
            return {
                "id": item["id"],
                "title": item["title"],
                "category": item["category"],
                "description": item["description"],
                "image": item["images"][0] if item.get("images") else "",
                "images": item.get("images", []),
                "client": item.get("client_name", ""),
                "year": item.get("created_at", "")[:4] if item.get("created_at") else "",
                "tags": item.get("tags", []),
                "slug": item.get("slug", ""),
            }
            
    except httpx.HTTPError as e:
        logger.error(f"Failed to fetch portfolio item from Direct-Online: {e}")
        raise HTTPException(status_code=502, detail="Could not fetch portfolio item")

# Get site settings (for dynamic site name, colors, etc.)
@api_router.get("/settings")
async def get_site_settings():
    """Fetch site settings from Direct-Online dashboard"""
    try:
        url = f"{DIRECT_ONLINE_API}/public/{TENANT_SLUG}/settings"
        
        async with httpx.AsyncClient() as client:
            response = await client.get(url, timeout=10.0)
            response.raise_for_status()
            return response.json()
            
    except httpx.HTTPError as e:
        logger.error(f"Failed to fetch settings from Direct-Online: {e}")
        # Return defaults if API fails
        return {
            "site_name": "Vermeer Design",
            "tagline": "Creatief Grafisch Ontwerp",
            "logo": "",
            "colors": {"primary": "#14b8a6", "accent": "#f59d0e"},
            "social": {}
        }

# Get testimonials
@api_router.get("/testimonials")
async def get_testimonials():
    """Fetch testimonials from Direct-Online dashboard"""
    try:
        url = f"{DIRECT_ONLINE_API}/public/{TENANT_SLUG}/testimonials"
        
        async with httpx.AsyncClient() as client:
            response = await client.get(url, timeout=10.0)
            response.raise_for_status()
            return response.json()
            
    except httpx.HTTPError as e:
        logger.error(f"Failed to fetch testimonials from Direct-Online: {e}")
        return []

# Submit contact form to Direct-Online
@api_router.post("/contact")
async def submit_contact(data: dict):
    """Submit contact form to Direct-Online dashboard"""
    try:
        url = f"{DIRECT_ONLINE_API}/public/{TENANT_SLUG}/contact"
        
        payload = {
            "name": data.get("name", ""),
            "email": data.get("email", ""),
            "message": data.get("message", ""),
            "source_page": "/contact"
        }
        
        async with httpx.AsyncClient() as client:
            response = await client.post(url, json=payload, timeout=10.0)
            response.raise_for_status()
            return {"message": "Bericht succesvol verzonden!"}
            
    except httpx.HTTPError as e:
        logger.error(f"Failed to submit contact form: {e}")
        raise HTTPException(status_code=502, detail="Could not send message")

# Admin redirect info
@api_router.get("/admin/redirect")
async def get_admin_redirect():
    """Return the Direct-Online dashboard URL for admin redirect"""
    return {
        "redirect_url": "https://ghl-connect-2.preview.emergentagent.com",
        "message": "Please login to the Direct-Online dashboard to manage your website"
    }

# Include the router
app.include_router(api_router)

# CORS
app.add_middleware(
    CORSMiddleware,
    allow_credentials=True,
    allow_origins=["*"],
    allow_methods=["*"],
    allow_headers=["*"],
)

@app.on_event("startup")
async def startup():
    logger.info(f"Connected to Direct-Online API: {DIRECT_ONLINE_API}")
    logger.info(f"Tenant slug: {TENANT_SLUG}")

# Note: No database connection needed anymore!
# All data is fetched from Direct-Online dashboard

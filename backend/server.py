from fastapi import FastAPI, APIRouter, HTTPException, Depends, status
from fastapi.security import HTTPBearer, HTTPAuthorizationCredentials
from dotenv import load_dotenv
from starlette.middleware.cors import CORSMiddleware
from motor.motor_asyncio import AsyncIOMotorClient
import os
import logging
from pathlib import Path
from pydantic import BaseModel, Field, EmailStr
from typing import List, Optional, Dict, Any
import uuid
from datetime import datetime, timezone, timedelta
import jwt
import bcrypt

ROOT_DIR = Path(__file__).parent
load_dotenv(ROOT_DIR / '.env')

# MongoDB connection
mongo_url = os.environ['MONGO_URL']
client = AsyncIOMotorClient(mongo_url)

# Master database for agency
master_db = client[os.environ.get('DB_NAME', 'direct_online_master')]

# JWT Configuration
JWT_SECRET = os.environ.get('JWT_SECRET', 'direct-online-secret-key-change-in-production')
JWT_ALGORITHM = "HS256"
JWT_EXPIRATION_HOURS = 24

# Create the main app
app = FastAPI(title="Direct-Online Dashboard API")

# Create routers
api_router = APIRouter(prefix="/api")
security = HTTPBearer()

# Configure logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s'
)
logger = logging.getLogger(__name__)

# ============== MODELS ==============

# Auth Models
class UserLogin(BaseModel):
    email: EmailStr
    password: str

class UserCreate(BaseModel):
    email: EmailStr
    password: str
    name: str
    role: str = "client"  # client, agency_admin

class UserResponse(BaseModel):
    id: str
    email: str
    name: str
    role: str
    tenant_id: Optional[str] = None
    language: str = "nl"

class TokenResponse(BaseModel):
    access_token: str
    token_type: str = "bearer"
    user: UserResponse

# Tenant Models
class TenantCreate(BaseModel):
    name: str
    slug: str
    domain: Optional[str] = None
    plan: str = "early-bird"
    contact_email: EmailStr
    contact_name: str

class TenantResponse(BaseModel):
    id: str
    name: str
    slug: str
    domain: Optional[str] = None
    plan: str
    status: str
    contact_email: str
    contact_name: str
    setup_fee_paid: bool
    created_at: str
    modules: Dict[str, Any]

class TenantUpdate(BaseModel):
    name: Optional[str] = None
    domain: Optional[str] = None
    plan: Optional[str] = None
    status: Optional[str] = None
    modules: Optional[Dict[str, Any]] = None

# Portfolio Models
class PortfolioItemCreate(BaseModel):
    title: str
    description: Optional[str] = ""
    client_name: Optional[str] = ""
    category: Optional[str] = ""
    images: List[str] = []
    tags: List[str] = []
    status: str = "draft"
    order: int = 0

class PortfolioItemResponse(BaseModel):
    id: str
    title: str
    slug: str
    description: str
    client_name: str
    category: str
    images: List[str]
    tags: List[str]
    status: str
    order: int
    created_at: str
    updated_at: str

class PortfolioItemUpdate(BaseModel):
    title: Optional[str] = None
    description: Optional[str] = None
    client_name: Optional[str] = None
    category: Optional[str] = None
    images: Optional[List[str]] = None
    tags: Optional[List[str]] = None
    status: Optional[str] = None
    order: Optional[int] = None

# Testimonial Models
class TestimonialCreate(BaseModel):
    client_name: str
    client_title: Optional[str] = ""
    client_company: Optional[str] = ""
    client_photo: Optional[str] = ""
    quote: str
    rating: int = 5
    status: str = "draft"
    order: int = 0

class TestimonialResponse(BaseModel):
    id: str
    client_name: str
    client_title: str
    client_company: str
    client_photo: str
    quote: str
    rating: int
    status: str
    order: int
    created_at: str

class TestimonialUpdate(BaseModel):
    client_name: Optional[str] = None
    client_title: Optional[str] = None
    client_company: Optional[str] = None
    client_photo: Optional[str] = None
    quote: Optional[str] = None
    rating: Optional[int] = None
    status: Optional[str] = None
    order: Optional[int] = None

# Page Models
class PageCreate(BaseModel):
    title: str
    content: Dict[str, Any] = {}
    seo: Dict[str, str] = {}
    status: str = "draft"
    order: int = 0

class PageResponse(BaseModel):
    id: str
    title: str
    slug: str
    content: Dict[str, Any]
    seo: Dict[str, str]
    status: str
    order: int
    created_at: str
    updated_at: str

class PageUpdate(BaseModel):
    title: Optional[str] = None
    content: Optional[Dict[str, Any]] = None
    seo: Optional[Dict[str, str]] = None
    status: Optional[str] = None
    order: Optional[int] = None

# Form Submission Models
class FormSubmissionResponse(BaseModel):
    id: str
    name: str
    email: str
    message: str
    source_page: str
    read: bool
    archived: bool
    created_at: str

class FormSubmissionCreate(BaseModel):
    name: str
    email: EmailStr
    message: str
    source_page: str = "/"

# Feedback Models
class FeedbackCreate(BaseModel):
    type: str  # bug, feature, general
    message: str
    page: Optional[str] = ""

class FeedbackResponse(BaseModel):
    id: str
    type: str
    message: str
    page: str
    status: str
    created_at: str
    user_name: str
    tenant_name: str

# Settings Models
class SettingsUpdate(BaseModel):
    site_name: Optional[str] = None
    tagline: Optional[str] = None
    logo: Optional[str] = None
    colors: Optional[Dict[str, str]] = None
    social: Optional[Dict[str, str]] = None

class SettingsResponse(BaseModel):
    site_name: str
    tagline: str
    logo: str
    colors: Dict[str, str]
    social: Dict[str, str]
    modules: Dict[str, Any]

# ============== HELPERS ==============

def create_slug(title: str) -> str:
    """Create URL-friendly slug from title"""
    import re
    slug = title.lower()
    slug = re.sub(r'[^a-z0-9\s-]', '', slug)
    slug = re.sub(r'[\s_]+', '-', slug)
    slug = re.sub(r'-+', '-', slug)
    return slug.strip('-')

def hash_password(password: str) -> str:
    return bcrypt.hashpw(password.encode('utf-8'), bcrypt.gensalt()).decode('utf-8')

def verify_password(password: str, hashed: str) -> bool:
    return bcrypt.checkpw(password.encode('utf-8'), hashed.encode('utf-8'))

def create_token(user_data: dict) -> str:
    payload = {
        **user_data,
        "exp": datetime.now(timezone.utc) + timedelta(hours=JWT_EXPIRATION_HOURS)
    }
    return jwt.encode(payload, JWT_SECRET, algorithm=JWT_ALGORITHM)

async def get_current_user(credentials: HTTPAuthorizationCredentials = Depends(security)) -> dict:
    try:
        token = credentials.credentials
        payload = jwt.decode(token, JWT_SECRET, algorithms=[JWT_ALGORITHM])
        return payload
    except jwt.ExpiredSignatureError:
        raise HTTPException(status_code=401, detail="Token verlopen")
    except jwt.InvalidTokenError:
        raise HTTPException(status_code=401, detail="Ongeldige token")

def get_client_db(tenant_slug: str):
    """Get database for a specific tenant/client"""
    return client[f"client_{tenant_slug}_db"]

# ============== AUTH ROUTES ==============

@api_router.post("/auth/login", response_model=TokenResponse)
async def login(credentials: UserLogin):
    user = await master_db.users.find_one({"email": credentials.email})
    if not user or not verify_password(credentials.password, user["password"]):
        raise HTTPException(status_code=401, detail="Ongeldige inloggegevens")
    
    user_data = {
        "id": user["id"],
        "email": user["email"],
        "name": user["name"],
        "role": user["role"],
        "tenant_id": user.get("tenant_id"),
        "language": user.get("language", "nl")
    }
    
    token = create_token(user_data)
    return TokenResponse(
        access_token=token,
        user=UserResponse(**user_data)
    )

@api_router.get("/auth/me", response_model=UserResponse)
async def get_me(current_user: dict = Depends(get_current_user)):
    return UserResponse(**current_user)

@api_router.put("/auth/language")
async def update_language(language: str, current_user: dict = Depends(get_current_user)):
    if language not in ["nl", "en"]:
        raise HTTPException(status_code=400, detail="Ongeldige taal")
    
    await master_db.users.update_one(
        {"id": current_user["id"]},
        {"$set": {"language": language}}
    )
    return {"message": "Taal bijgewerkt", "language": language}

# ============== AGENCY ADMIN ROUTES ==============

@api_router.get("/admin/tenants", response_model=List[TenantResponse])
async def get_all_tenants(current_user: dict = Depends(get_current_user)):
    if current_user["role"] != "agency_admin":
        raise HTTPException(status_code=403, detail="Geen toegang")
    
    tenants = await master_db.tenants.find({}, {"_id": 0}).to_list(1000)
    return tenants

@api_router.post("/admin/tenants", response_model=TenantResponse, status_code=201)
async def create_tenant(tenant: TenantCreate, current_user: dict = Depends(get_current_user)):
    if current_user["role"] != "agency_admin":
        raise HTTPException(status_code=403, detail="Geen toegang")
    
    # Check if slug exists
    existing = await master_db.tenants.find_one({"slug": tenant.slug})
    if existing:
        raise HTTPException(status_code=400, detail="Slug bestaat al")
    
    # Default modules based on plan
    modules = {
        "pages": {"enabled": True},
        "portfolio": {"enabled": True},
        "testimonials": {"enabled": True},
        "blog": {"enabled": False},
        "forms": {"enabled": True, "max": 1},
        "media": {"enabled": True, "max_storage_mb": 500}
    }
    
    if tenant.plan in ["growth", "webshop"]:
        modules["blog"] = {"enabled": True}
        modules["forms"]["max"] = 3 if tenant.plan == "growth" else 5
        modules["media"]["max_storage_mb"] = 5000 if tenant.plan == "growth" else 10000
    
    tenant_doc = {
        "id": str(uuid.uuid4()),
        "name": tenant.name,
        "slug": tenant.slug,
        "domain": tenant.domain,
        "plan": tenant.plan,
        "status": "active",
        "contact_email": tenant.contact_email,
        "contact_name": tenant.contact_name,
        "setup_fee_paid": False,
        "modules": modules,
        "created_at": datetime.now(timezone.utc).isoformat()
    }
    
    await master_db.tenants.insert_one(tenant_doc)
    
    # Initialize client database with default settings
    client_db = get_client_db(tenant.slug)
    await client_db.settings.insert_one({
        "site_name": tenant.name,
        "tagline": "",
        "logo": "",
        "colors": {"primary": "#129387", "accent": "#f59d0e"},
        "social": {},
        "modules": modules
    })
    
    del tenant_doc["_id"]
    return tenant_doc

@api_router.get("/admin/tenants/{tenant_id}", response_model=TenantResponse)
async def get_tenant(tenant_id: str, current_user: dict = Depends(get_current_user)):
    if current_user["role"] != "agency_admin":
        raise HTTPException(status_code=403, detail="Geen toegang")
    
    tenant = await master_db.tenants.find_one({"id": tenant_id}, {"_id": 0})
    if not tenant:
        raise HTTPException(status_code=404, detail="Klant niet gevonden")
    return tenant

@api_router.put("/admin/tenants/{tenant_id}", response_model=TenantResponse)
async def update_tenant(tenant_id: str, update: TenantUpdate, current_user: dict = Depends(get_current_user)):
    if current_user["role"] != "agency_admin":
        raise HTTPException(status_code=403, detail="Geen toegang")
    
    update_data = {k: v for k, v in update.model_dump().items() if v is not None}
    if not update_data:
        raise HTTPException(status_code=400, detail="Geen gegevens om bij te werken")
    
    result = await master_db.tenants.update_one(
        {"id": tenant_id},
        {"$set": update_data}
    )
    
    if result.matched_count == 0:
        raise HTTPException(status_code=404, detail="Klant niet gevonden")
    
    tenant = await master_db.tenants.find_one({"id": tenant_id}, {"_id": 0})
    return tenant

@api_router.post("/admin/tenants/{tenant_id}/user")
async def create_tenant_user(tenant_id: str, user: UserCreate, current_user: dict = Depends(get_current_user)):
    if current_user["role"] != "agency_admin":
        raise HTTPException(status_code=403, detail="Geen toegang")
    
    tenant = await master_db.tenants.find_one({"id": tenant_id})
    if not tenant:
        raise HTTPException(status_code=404, detail="Klant niet gevonden")
    
    existing = await master_db.users.find_one({"email": user.email})
    if existing:
        raise HTTPException(status_code=400, detail="E-mail bestaat al")
    
    user_doc = {
        "id": str(uuid.uuid4()),
        "email": user.email,
        "password": hash_password(user.password),
        "name": user.name,
        "role": "client",
        "tenant_id": tenant_id,
        "tenant_slug": tenant["slug"],
        "language": "nl",
        "created_at": datetime.now(timezone.utc).isoformat()
    }
    
    await master_db.users.insert_one(user_doc)
    return {"message": "Gebruiker aangemaakt", "user_id": user_doc["id"]}

@api_router.get("/admin/feedback", response_model=List[FeedbackResponse])
async def get_all_feedback(current_user: dict = Depends(get_current_user)):
    if current_user["role"] != "agency_admin":
        raise HTTPException(status_code=403, detail="Geen toegang")
    
    feedback_list = await master_db.feedback.find({}, {"_id": 0}).sort("created_at", -1).to_list(1000)
    return feedback_list

# ============== CLIENT DASHBOARD ROUTES ==============

async def get_tenant_db(current_user: dict):
    """Get the database for the current user's tenant"""
    if current_user["role"] == "agency_admin":
        # Agency admins need to specify tenant
        raise HTTPException(status_code=400, detail="Geef tenant_slug op")
    
    tenant = await master_db.tenants.find_one({"id": current_user["tenant_id"]})
    if not tenant:
        raise HTTPException(status_code=404, detail="Tenant niet gevonden")
    
    return get_client_db(tenant["slug"]), tenant

# Portfolio Routes
@api_router.get("/portfolio", response_model=List[PortfolioItemResponse])
async def get_portfolio_items(current_user: dict = Depends(get_current_user)):
    client_db, _ = await get_tenant_db(current_user)
    items = await client_db.portfolio.find({}, {"_id": 0}).sort("order", 1).to_list(1000)
    return items

@api_router.post("/portfolio", response_model=PortfolioItemResponse, status_code=201)
async def create_portfolio_item(item: PortfolioItemCreate, current_user: dict = Depends(get_current_user)):
    client_db, _ = await get_tenant_db(current_user)
    
    now = datetime.now(timezone.utc).isoformat()
    item_doc = {
        "id": str(uuid.uuid4()),
        "slug": create_slug(item.title),
        **item.model_dump(),
        "created_at": now,
        "updated_at": now
    }
    
    await client_db.portfolio.insert_one(item_doc)
    del item_doc["_id"]
    return item_doc

@api_router.put("/portfolio/{item_id}", response_model=PortfolioItemResponse)
async def update_portfolio_item(item_id: str, update: PortfolioItemUpdate, current_user: dict = Depends(get_current_user)):
    client_db, _ = await get_tenant_db(current_user)
    
    update_data = {k: v for k, v in update.model_dump().items() if v is not None}
    if "title" in update_data:
        update_data["slug"] = create_slug(update_data["title"])
    update_data["updated_at"] = datetime.now(timezone.utc).isoformat()
    
    result = await client_db.portfolio.update_one(
        {"id": item_id},
        {"$set": update_data}
    )
    
    if result.matched_count == 0:
        raise HTTPException(status_code=404, detail="Item niet gevonden")
    
    item = await client_db.portfolio.find_one({"id": item_id}, {"_id": 0})
    return item

@api_router.delete("/portfolio/{item_id}")
async def delete_portfolio_item(item_id: str, current_user: dict = Depends(get_current_user)):
    client_db, _ = await get_tenant_db(current_user)
    
    result = await client_db.portfolio.delete_one({"id": item_id})
    if result.deleted_count == 0:
        raise HTTPException(status_code=404, detail="Item niet gevonden")
    
    return {"message": "Item verwijderd"}

# Testimonials Routes
@api_router.get("/testimonials", response_model=List[TestimonialResponse])
async def get_testimonials(current_user: dict = Depends(get_current_user)):
    client_db, _ = await get_tenant_db(current_user)
    items = await client_db.testimonials.find({}, {"_id": 0}).sort("order", 1).to_list(1000)
    return items

@api_router.post("/testimonials", response_model=TestimonialResponse, status_code=201)
async def create_testimonial(item: TestimonialCreate, current_user: dict = Depends(get_current_user)):
    client_db, _ = await get_tenant_db(current_user)
    
    item_doc = {
        "id": str(uuid.uuid4()),
        **item.model_dump(),
        "created_at": datetime.now(timezone.utc).isoformat()
    }
    
    await client_db.testimonials.insert_one(item_doc)
    del item_doc["_id"]
    return item_doc

@api_router.put("/testimonials/{item_id}", response_model=TestimonialResponse)
async def update_testimonial(item_id: str, update: TestimonialUpdate, current_user: dict = Depends(get_current_user)):
    client_db, _ = await get_tenant_db(current_user)
    
    update_data = {k: v for k, v in update.model_dump().items() if v is not None}
    
    result = await client_db.testimonials.update_one(
        {"id": item_id},
        {"$set": update_data}
    )
    
    if result.matched_count == 0:
        raise HTTPException(status_code=404, detail="Item niet gevonden")
    
    item = await client_db.testimonials.find_one({"id": item_id}, {"_id": 0})
    return item

@api_router.delete("/testimonials/{item_id}")
async def delete_testimonial(item_id: str, current_user: dict = Depends(get_current_user)):
    client_db, _ = await get_tenant_db(current_user)
    
    result = await client_db.testimonials.delete_one({"id": item_id})
    if result.deleted_count == 0:
        raise HTTPException(status_code=404, detail="Item niet gevonden")
    
    return {"message": "Testimonial verwijderd"}

# Pages Routes
@api_router.get("/pages", response_model=List[PageResponse])
async def get_pages(current_user: dict = Depends(get_current_user)):
    client_db, _ = await get_tenant_db(current_user)
    items = await client_db.pages.find({}, {"_id": 0}).sort("order", 1).to_list(1000)
    return items

@api_router.post("/pages", response_model=PageResponse, status_code=201)
async def create_page(item: PageCreate, current_user: dict = Depends(get_current_user)):
    client_db, _ = await get_tenant_db(current_user)
    
    now = datetime.now(timezone.utc).isoformat()
    item_doc = {
        "id": str(uuid.uuid4()),
        "slug": create_slug(item.title),
        **item.model_dump(),
        "created_at": now,
        "updated_at": now
    }
    
    await client_db.pages.insert_one(item_doc)
    del item_doc["_id"]
    return item_doc

@api_router.put("/pages/{item_id}", response_model=PageResponse)
async def update_page(item_id: str, update: PageUpdate, current_user: dict = Depends(get_current_user)):
    client_db, _ = await get_tenant_db(current_user)
    
    update_data = {k: v for k, v in update.model_dump().items() if v is not None}
    if "title" in update_data:
        update_data["slug"] = create_slug(update_data["title"])
    update_data["updated_at"] = datetime.now(timezone.utc).isoformat()
    
    result = await client_db.pages.update_one(
        {"id": item_id},
        {"$set": update_data}
    )
    
    if result.matched_count == 0:
        raise HTTPException(status_code=404, detail="Pagina niet gevonden")
    
    item = await client_db.pages.find_one({"id": item_id}, {"_id": 0})
    return item

@api_router.delete("/pages/{item_id}")
async def delete_page(item_id: str, current_user: dict = Depends(get_current_user)):
    client_db, _ = await get_tenant_db(current_user)
    
    result = await client_db.pages.delete_one({"id": item_id})
    if result.deleted_count == 0:
        raise HTTPException(status_code=404, detail="Pagina niet gevonden")
    
    return {"message": "Pagina verwijderd"}

# Form Submissions (Inbox)
@api_router.get("/inbox", response_model=List[FormSubmissionResponse])
async def get_inbox(current_user: dict = Depends(get_current_user)):
    client_db, _ = await get_tenant_db(current_user)
    items = await client_db.form_submissions.find({"archived": False}, {"_id": 0}).sort("created_at", -1).to_list(1000)
    return items

@api_router.put("/inbox/{item_id}/read")
async def mark_as_read(item_id: str, current_user: dict = Depends(get_current_user)):
    client_db, _ = await get_tenant_db(current_user)
    
    result = await client_db.form_submissions.update_one(
        {"id": item_id},
        {"$set": {"read": True}}
    )
    
    if result.matched_count == 0:
        raise HTTPException(status_code=404, detail="Bericht niet gevonden")
    
    return {"message": "Gemarkeerd als gelezen"}

@api_router.put("/inbox/{item_id}/archive")
async def archive_submission(item_id: str, current_user: dict = Depends(get_current_user)):
    client_db, _ = await get_tenant_db(current_user)
    
    result = await client_db.form_submissions.update_one(
        {"id": item_id},
        {"$set": {"archived": True}}
    )
    
    if result.matched_count == 0:
        raise HTTPException(status_code=404, detail="Bericht niet gevonden")
    
    return {"message": "Gearchiveerd"}

# Public form submission endpoint (no auth required)
@api_router.post("/public/form/{tenant_slug}")
async def submit_form(tenant_slug: str, submission: FormSubmissionCreate):
    tenant = await master_db.tenants.find_one({"slug": tenant_slug})
    if not tenant:
        raise HTTPException(status_code=404, detail="Website niet gevonden")
    
    client_db = get_client_db(tenant_slug)
    
    submission_doc = {
        "id": str(uuid.uuid4()),
        **submission.model_dump(),
        "read": False,
        "archived": False,
        "created_at": datetime.now(timezone.utc).isoformat()
    }
    
    await client_db.form_submissions.insert_one(submission_doc)
    return {"message": "Bericht ontvangen", "id": submission_doc["id"]}

# Settings
@api_router.get("/settings", response_model=SettingsResponse)
async def get_settings(current_user: dict = Depends(get_current_user)):
    client_db, tenant = await get_tenant_db(current_user)
    settings = await client_db.settings.find_one({}, {"_id": 0})
    
    if not settings:
        # Return defaults
        return SettingsResponse(
            site_name=tenant["name"],
            tagline="",
            logo="",
            colors={"primary": "#129387", "accent": "#f59d0e"},
            social={},
            modules=tenant["modules"]
        )
    
    settings["modules"] = tenant["modules"]
    return settings

@api_router.put("/settings", response_model=SettingsResponse)
async def update_settings(update: SettingsUpdate, current_user: dict = Depends(get_current_user)):
    client_db, tenant = await get_tenant_db(current_user)
    
    update_data = {k: v for k, v in update.model_dump().items() if v is not None}
    
    await client_db.settings.update_one(
        {},
        {"$set": update_data},
        upsert=True
    )
    
    settings = await client_db.settings.find_one({}, {"_id": 0})
    settings["modules"] = tenant["modules"]
    return settings

# Feedback
@api_router.post("/feedback")
async def submit_feedback(feedback: FeedbackCreate, current_user: dict = Depends(get_current_user)):
    _, tenant = await get_tenant_db(current_user)
    
    feedback_doc = {
        "id": str(uuid.uuid4()),
        **feedback.model_dump(),
        "status": "new",
        "user_id": current_user["id"],
        "user_name": current_user["name"],
        "tenant_id": tenant["id"],
        "tenant_name": tenant["name"],
        "created_at": datetime.now(timezone.utc).isoformat()
    }
    
    await master_db.feedback.insert_one(feedback_doc)
    return {"message": "Feedback ontvangen", "id": feedback_doc["id"]}

@api_router.get("/feedback", response_model=List[FeedbackResponse])
async def get_my_feedback(current_user: dict = Depends(get_current_user)):
    feedback_list = await master_db.feedback.find(
        {"user_id": current_user["id"]},
        {"_id": 0}
    ).sort("created_at", -1).to_list(100)
    return feedback_list

# Dashboard Stats
@api_router.get("/dashboard/stats")
async def get_dashboard_stats(current_user: dict = Depends(get_current_user)):
    client_db, tenant = await get_tenant_db(current_user)
    
    portfolio_count = await client_db.portfolio.count_documents({})
    testimonials_count = await client_db.testimonials.count_documents({})
    pages_count = await client_db.pages.count_documents({})
    unread_count = await client_db.form_submissions.count_documents({"read": False, "archived": False})
    
    return {
        "portfolio_count": portfolio_count,
        "testimonials_count": testimonials_count,
        "pages_count": pages_count,
        "unread_messages": unread_count,
        "plan": tenant["plan"],
        "modules": tenant["modules"]
    }

# Health check
@api_router.get("/health")
async def health_check():
    return {"status": "healthy", "service": "Direct-Online Dashboard API"}

# ============== SEED DATA ==============

@api_router.post("/seed")
async def seed_data():
    """Seed initial data for testing"""
    
    # Check if already seeded
    existing_admin = await master_db.users.find_one({"email": "admin@direct-online.nl"})
    if existing_admin:
        return {"message": "Data al aanwezig"}
    
    # Create agency admin
    admin_user = {
        "id": str(uuid.uuid4()),
        "email": "admin@direct-online.nl",
        "password": hash_password("admin123"),
        "name": "Direct-Online Admin",
        "role": "agency_admin",
        "tenant_id": None,
        "language": "nl",
        "created_at": datetime.now(timezone.utc).isoformat()
    }
    await master_db.users.insert_one(admin_user)
    
    # Create demo tenant
    demo_tenant = {
        "id": str(uuid.uuid4()),
        "name": "Demo Fotograaf",
        "slug": "demo-fotograaf",
        "domain": "demo-fotograaf.nl",
        "plan": "early-bird",
        "status": "active",
        "contact_email": "jan@demo-fotograaf.nl",
        "contact_name": "Jan de Vries",
        "setup_fee_paid": True,
        "modules": {
            "pages": {"enabled": True},
            "portfolio": {"enabled": True},
            "testimonials": {"enabled": True},
            "blog": {"enabled": False},
            "forms": {"enabled": True, "max": 1},
            "media": {"enabled": True, "max_storage_mb": 500}
        },
        "created_at": datetime.now(timezone.utc).isoformat()
    }
    await master_db.tenants.insert_one(demo_tenant)
    
    # Create demo client user
    client_user = {
        "id": str(uuid.uuid4()),
        "email": "jan@demo-fotograaf.nl",
        "password": hash_password("demo123"),
        "name": "Jan de Vries",
        "role": "client",
        "tenant_id": demo_tenant["id"],
        "tenant_slug": "demo-fotograaf",
        "language": "nl",
        "created_at": datetime.now(timezone.utc).isoformat()
    }
    await master_db.users.insert_one(client_user)
    
    # Initialize client database
    client_db = get_client_db("demo-fotograaf")
    
    # Settings
    await client_db.settings.insert_one({
        "site_name": "Jan de Vries Fotografie",
        "tagline": "Professionele fotografie voor elk moment",
        "logo": "",
        "colors": {"primary": "#129387", "accent": "#f59d0e"},
        "social": {"instagram": "https://instagram.com/jandevries", "linkedin": "https://linkedin.com/in/jandevries"},
        "modules": demo_tenant["modules"]
    })
    
    # Sample portfolio items
    portfolio_items = [
        {
            "id": str(uuid.uuid4()),
            "title": "Bruiloft van Lisa & Mark",
            "slug": "bruiloft-lisa-mark",
            "description": "Een prachtige zomerbruiloft in de tuinen van kasteel Groeneveld.",
            "client_name": "Lisa & Mark",
            "category": "Bruiloften",
            "images": ["https://images.unsplash.com/photo-1519741497674-611481863552?w=800"],
            "tags": ["bruiloft", "zomer", "kasteel"],
            "status": "published",
            "order": 1,
            "created_at": datetime.now(timezone.utc).isoformat(),
            "updated_at": datetime.now(timezone.utc).isoformat()
        },
        {
            "id": str(uuid.uuid4()),
            "title": "Productfotografie voor TechStart",
            "slug": "productfotografie-techstart",
            "description": "Moderne productfotografie voor een tech startup.",
            "client_name": "TechStart BV",
            "category": "Product",
            "images": ["https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800"],
            "tags": ["product", "tech", "studio"],
            "status": "published",
            "order": 2,
            "created_at": datetime.now(timezone.utc).isoformat(),
            "updated_at": datetime.now(timezone.utc).isoformat()
        }
    ]
    await client_db.portfolio.insert_many(portfolio_items)
    
    # Sample testimonials
    testimonials = [
        {
            "id": str(uuid.uuid4()),
            "client_name": "Lisa van den Berg",
            "client_title": "Bruid",
            "client_company": "",
            "client_photo": "",
            "quote": "Jan heeft onze trouwdag perfect vastgelegd. De foto's zijn prachtig en vol emotie!",
            "rating": 5,
            "status": "published",
            "order": 1,
            "created_at": datetime.now(timezone.utc).isoformat()
        },
        {
            "id": str(uuid.uuid4()),
            "client_name": "Peter Bakker",
            "client_title": "CEO",
            "client_company": "TechStart BV",
            "client_photo": "",
            "quote": "Professioneel, creatief en altijd op tijd. Aanrader!",
            "rating": 5,
            "status": "published",
            "order": 2,
            "created_at": datetime.now(timezone.utc).isoformat()
        }
    ]
    await client_db.testimonials.insert_many(testimonials)
    
    # Sample pages
    pages = [
        {
            "id": str(uuid.uuid4()),
            "title": "Over Mij",
            "slug": "over-mij",
            "content": {"blocks": [{"type": "paragraph", "text": "Welkom! Ik ben Jan, een gepassioneerde fotograaf uit Groningen."}]},
            "seo": {"title": "Over Mij - Jan de Vries Fotografie", "description": "Leer meer over Jan de Vries"},
            "status": "published",
            "order": 1,
            "created_at": datetime.now(timezone.utc).isoformat(),
            "updated_at": datetime.now(timezone.utc).isoformat()
        },
        {
            "id": str(uuid.uuid4()),
            "title": "Diensten",
            "slug": "diensten",
            "content": {"blocks": [{"type": "paragraph", "text": "Ik bied verschillende fotografiediensten aan."}]},
            "seo": {"title": "Diensten - Jan de Vries Fotografie", "description": "Bekijk mijn diensten"},
            "status": "published",
            "order": 2,
            "created_at": datetime.now(timezone.utc).isoformat(),
            "updated_at": datetime.now(timezone.utc).isoformat()
        }
    ]
    await client_db.pages.insert_many(pages)
    
    # Sample form submissions
    submissions = [
        {
            "id": str(uuid.uuid4()),
            "name": "Anna de Groot",
            "email": "anna@example.nl",
            "message": "Hallo Jan, ik ben geïnteresseerd in een fotoshoot voor mijn bedrijf. Kun je me meer informatie sturen?",
            "source_page": "/contact",
            "read": False,
            "archived": False,
            "created_at": datetime.now(timezone.utc).isoformat()
        }
    ]
    await client_db.form_submissions.insert_many(submissions)
    
    return {
        "message": "Seed data aangemaakt",
        "admin_email": "admin@direct-online.nl",
        "admin_password": "admin123",
        "client_email": "jan@demo-fotograaf.nl",
        "client_password": "demo123"
    }

# Include router
app.include_router(api_router)

# CORS
app.add_middleware(
    CORSMiddleware,
    allow_credentials=True,
    allow_origins=os.environ.get('CORS_ORIGINS', '*').split(','),
    allow_methods=["*"],
    allow_headers=["*"],
)

@app.on_event("shutdown")
async def shutdown_db_client():
    client.close()

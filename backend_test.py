#!/usr/bin/env python3
"""
Backend API Testing for Direct-Online CMS Dashboard
Tests all endpoints with client and admin credentials
"""

import requests
import sys
import json
from datetime import datetime

class DirectOnlineTester:
    def __init__(self, base_url="https://form-fix-debug-1.preview.emergentagent.com"):
        self.base_url = base_url
        self.api_url = f"{base_url}/api"
        self.client_token = None
        self.admin_token = None
        self.tests_run = 0
        self.tests_passed = 0
        self.failed_tests = []
        
        # Test credentials
        self.client_creds = {
            "email": "jan@demo-fotograaf.nl",
            "password": "demo123"
        }
        self.admin_creds = {
            "email": "admin@direct-online.nl", 
            "password": "admin123"
        }

    def log(self, message, level="INFO"):
        timestamp = datetime.now().strftime("%H:%M:%S")
        print(f"[{timestamp}] {level}: {message}")

    def run_test(self, name, method, endpoint, expected_status, data=None, headers=None, auth_token=None):
        """Run a single API test"""
        url = f"{self.api_url}/{endpoint}"
        test_headers = {'Content-Type': 'application/json'}
        
        if auth_token:
            test_headers['Authorization'] = f'Bearer {auth_token}'
        if headers:
            test_headers.update(headers)

        self.tests_run += 1
        self.log(f"Testing {name}...")
        
        try:
            if method == 'GET':
                response = requests.get(url, headers=test_headers, timeout=10)
            elif method == 'POST':
                response = requests.post(url, json=data, headers=test_headers, timeout=10)
            elif method == 'PUT':
                response = requests.put(url, json=data, headers=test_headers, timeout=10)
            elif method == 'DELETE':
                response = requests.delete(url, headers=test_headers, timeout=10)

            success = response.status_code == expected_status
            if success:
                self.tests_passed += 1
                self.log(f"✅ {name} - Status: {response.status_code}", "PASS")
                return True, response.json() if response.content else {}
            else:
                self.log(f"❌ {name} - Expected {expected_status}, got {response.status_code}", "FAIL")
                self.log(f"   Response: {response.text[:200]}", "FAIL")
                self.failed_tests.append({
                    "test": name,
                    "expected": expected_status,
                    "actual": response.status_code,
                    "response": response.text[:200]
                })
                return False, {}

        except Exception as e:
            self.log(f"❌ {name} - Error: {str(e)}", "ERROR")
            self.failed_tests.append({
                "test": name,
                "error": str(e)
            })
            return False, {}

    def test_health_check(self):
        """Test health endpoint"""
        return self.run_test("Health Check", "GET", "health", 200)

    def test_seed_data(self):
        """Seed initial data"""
        return self.run_test("Seed Data", "POST", "seed", 200)

    def test_client_login(self):
        """Test client login"""
        success, response = self.run_test(
            "Client Login",
            "POST", 
            "auth/login",
            200,
            data=self.client_creds
        )
        if success and 'access_token' in response:
            self.client_token = response['access_token']
            self.log(f"✅ Client token obtained", "AUTH")
            return True
        return False

    def test_admin_login(self):
        """Test admin login"""
        success, response = self.run_test(
            "Admin Login",
            "POST",
            "auth/login", 
            200,
            data=self.admin_creds
        )
        if success and 'access_token' in response:
            self.admin_token = response['access_token']
            self.log(f"✅ Admin token obtained", "AUTH")
            return True
        return False

    def test_client_endpoints(self):
        """Test client-specific endpoints"""
        if not self.client_token:
            self.log("❌ No client token available", "ERROR")
            return False

        # Dashboard stats
        self.run_test("Dashboard Stats", "GET", "dashboard/stats", 200, auth_token=self.client_token)
        
        # Portfolio endpoints
        self.run_test("Get Portfolio", "GET", "portfolio", 200, auth_token=self.client_token)
        
        # Create portfolio item
        portfolio_data = {
            "title": "Test Portfolio Item",
            "description": "Test description",
            "client_name": "Test Client",
            "category": "Test Category",
            "images": ["https://example.com/test.jpg"],
            "tags": ["test", "portfolio"],
            "status": "draft"
        }
        success, portfolio_response = self.run_test(
            "Create Portfolio Item", "POST", "portfolio", 201, 
            data=portfolio_data, auth_token=self.client_token
        )
        
        portfolio_id = None
        if success and 'id' in portfolio_response:
            portfolio_id = portfolio_response['id']
            
            # Update portfolio item
            update_data = {"title": "Updated Test Portfolio"}
            self.run_test(
                "Update Portfolio Item", "PUT", f"portfolio/{portfolio_id}", 200,
                data=update_data, auth_token=self.client_token
            )
            
            # Delete portfolio item
            self.run_test(
                "Delete Portfolio Item", "DELETE", f"portfolio/{portfolio_id}", 200,
                auth_token=self.client_token
            )

        # Testimonials endpoints
        self.run_test("Get Testimonials", "GET", "testimonials", 200, auth_token=self.client_token)
        
        # Create testimonial
        testimonial_data = {
            "client_name": "Test Client",
            "client_title": "CEO",
            "client_company": "Test Company",
            "quote": "Great service!",
            "rating": 5,
            "status": "draft"
        }
        success, testimonial_response = self.run_test(
            "Create Testimonial", "POST", "testimonials", 201,
            data=testimonial_data, auth_token=self.client_token
        )
        
        testimonial_id = None
        if success and 'id' in testimonial_response:
            testimonial_id = testimonial_response['id']
            
            # Update testimonial
            update_data = {"quote": "Updated great service!"}
            self.run_test(
                "Update Testimonial", "PUT", f"testimonials/{testimonial_id}", 200,
                data=update_data, auth_token=self.client_token
            )
            
            # Delete testimonial
            self.run_test(
                "Delete Testimonial", "DELETE", f"testimonials/{testimonial_id}", 200,
                auth_token=self.client_token
            )

        # Pages endpoints
        self.run_test("Get Pages", "GET", "pages", 200, auth_token=self.client_token)
        
        # Create page
        page_data = {
            "title": "Test Page",
            "content": {"blocks": [{"type": "paragraph", "text": "Test content"}]},
            "seo": {"title": "Test Page", "description": "Test description"},
            "status": "draft"
        }
        success, page_response = self.run_test(
            "Create Page", "POST", "pages", 201,
            data=page_data, auth_token=self.client_token
        )
        
        page_id = None
        if success and 'id' in page_response:
            page_id = page_response['id']
            
            # Update page
            update_data = {"title": "Updated Test Page"}
            self.run_test(
                "Update Page", "PUT", f"pages/{page_id}", 200,
                data=update_data, auth_token=self.client_token
            )
            
            # Delete page
            self.run_test(
                "Delete Page", "DELETE", f"pages/{page_id}", 200,
                auth_token=self.client_token
            )

        # Inbox endpoints
        self.run_test("Get Inbox", "GET", "inbox", 200, auth_token=self.client_token)
        
        # Settings endpoints
        self.run_test("Get Settings", "GET", "settings", 200, auth_token=self.client_token)
        
        # Update settings
        settings_data = {
            "site_name": "Updated Site Name",
            "tagline": "Updated tagline"
        }
        self.run_test(
            "Update Settings", "PUT", "settings", 200,
            data=settings_data, auth_token=self.client_token
        )
        
        # Feedback endpoints
        self.run_test("Get My Feedback", "GET", "feedback", 200, auth_token=self.client_token)
        
        # Submit feedback
        feedback_data = {
            "type": "bug",
            "message": "Test feedback message",
            "page": "/dashboard"
        }
        self.run_test(
            "Submit Feedback", "POST", "feedback", 200,
            data=feedback_data, auth_token=self.client_token
        )

        # Auth endpoints
        self.run_test("Get Current User", "GET", "auth/me", 200, auth_token=self.client_token)
        
        # Update language
        self.run_test(
            "Update Language", "PUT", "auth/language?language=en", 200,
            auth_token=self.client_token
        )

    def test_admin_endpoints(self):
        """Test admin-specific endpoints"""
        if not self.admin_token:
            self.log("❌ No admin token available", "ERROR")
            return False

        # Get all tenants
        self.run_test("Get All Tenants", "GET", "admin/tenants", 200, auth_token=self.admin_token)
        
        # Create tenant
        tenant_data = {
            "name": "Test Tenant",
            "slug": "test-tenant",
            "domain": "test-tenant.nl",
            "plan": "starter",
            "contact_email": "test@test.nl",
            "contact_name": "Test User"
        }
        success, tenant_response = self.run_test(
            "Create Tenant", "POST", "admin/tenants", 201,
            data=tenant_data, auth_token=self.admin_token
        )
        
        tenant_id = None
        if success and 'id' in tenant_response:
            tenant_id = tenant_response['id']
            
            # Get specific tenant
            self.run_test(
                "Get Tenant", "GET", f"admin/tenants/{tenant_id}", 200,
                auth_token=self.admin_token
            )
            
            # Update tenant
            update_data = {"name": "Updated Test Tenant"}
            self.run_test(
                "Update Tenant", "PUT", f"admin/tenants/{tenant_id}", 200,
                data=update_data, auth_token=self.admin_token
            )
            
            # Create user for tenant
            user_data = {
                "email": "testuser@test.nl",
                "password": "testpass123",
                "name": "Test User"
            }
            self.run_test(
                "Create Tenant User", "POST", f"admin/tenants/{tenant_id}/user", 200,
                data=user_data, auth_token=self.admin_token
            )

        # Get all feedback
        self.run_test("Get All Feedback", "GET", "admin/feedback", 200, auth_token=self.admin_token)

    def test_public_endpoints(self):
        """Test public endpoints (no auth required)"""
        # Test form submission (requires tenant slug)
        form_data = {
            "name": "Test Submitter",
            "email": "test@example.com",
            "message": "Test form submission",
            "source_page": "/contact"
        }
        self.run_test(
            "Public Form Submission", "POST", "public/form/demo-fotograaf", 200,
            data=form_data
        )

    def run_all_tests(self):
        """Run all tests in sequence"""
        self.log("🚀 Starting Direct-Online CMS API Tests", "START")
        
        # Health check
        self.test_health_check()
        
        # Seed data first
        self.test_seed_data()
        
        # Authentication tests
        client_login_success = self.test_client_login()
        admin_login_success = self.test_admin_login()
        
        # Client endpoint tests
        if client_login_success:
            self.test_client_endpoints()
        else:
            self.log("❌ Skipping client tests - login failed", "SKIP")
        
        # Admin endpoint tests  
        if admin_login_success:
            self.test_admin_endpoints()
        else:
            self.log("❌ Skipping admin tests - login failed", "SKIP")
        
        # Public endpoint tests
        self.test_public_endpoints()
        
        # Print results
        self.print_results()
        
        return self.tests_passed == self.tests_run

    def print_results(self):
        """Print test results summary"""
        self.log("=" * 60, "RESULTS")
        self.log(f"Tests Run: {self.tests_run}", "RESULTS")
        self.log(f"Tests Passed: {self.tests_passed}", "RESULTS")
        self.log(f"Tests Failed: {len(self.failed_tests)}", "RESULTS")
        self.log(f"Success Rate: {(self.tests_passed/self.tests_run*100):.1f}%", "RESULTS")
        
        if self.failed_tests:
            self.log("\n❌ Failed Tests:", "RESULTS")
            for test in self.failed_tests:
                error_msg = test.get('error', f"Expected {test.get('expected')}, got {test.get('actual')}")
                self.log(f"  - {test['test']}: {error_msg}", "RESULTS")

def main():
    tester = DirectOnlineTester()
    success = tester.run_all_tests()
    return 0 if success else 1

if __name__ == "__main__":
    sys.exit(main())
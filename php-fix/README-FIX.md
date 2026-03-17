# Contact Form Fix — Direct-Online

## The Bug
After the database migration, the `formulieren_DO` table column was renamed from `klant_id` to `client_id`.
The `submit-form.php` was still using the old column name `klant_id`, causing every INSERT to fail.

## The Fix (1 line change)
In `submit-form.php`, line ~46:

**Before (broken):**
```sql
INSERT INTO formulieren_DO (klant_id, naam, email, telefoon, bericht)
```

**After (fixed):**
```sql
INSERT INTO formulieren_DO (client_id, naam, email, telefoon, bericht)
```

## How to Deploy
1. Upload the fixed `submit-form.php` to replace the current one on your Hostinger server
2. `db.php` is unchanged — included for completeness
3. Test by submitting a contact form on any client website

## Verification
After deploying, submit a test form and check:
```sql
SELECT * FROM formulieren_DO ORDER BY aangemaakt_op DESC LIMIT 5;
```
You should see the new submission with the correct `client_id`.

## About Table Naming Inconsistency
You noted some tables have `_DO` suffix and some don't:
- **With `_DO`**: `klanten_DO`, `formulieren_DO`, `projects_DO`, `users_DO`, `media_DO`
- **Without `_DO`**: `agency_users`, `client_settings`, `sessions`, `testimonials`

This is from the migration mixing old tables (kept `_DO`) with new ones (no suffix).
**Recommendation**: Leave as-is for now. When we build the Laravel app, the code will handle both naming conventions. We can standardize later without breaking live sites.

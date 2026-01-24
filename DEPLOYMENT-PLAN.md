# Deployment Plan - TSM Theme

## Overview

This document outlines the deployment workflow for the TSM Theme, including URL management between local development and production environments.

## Environment URLs

- **Local Development:** `http://localhost:8080`
- **Production:** `https://terryshaguy.org` (or `http://terryshaguy.org`)

## Development Workflow

### Starting Development

1. Start Docker containers:
   ```bash
   docker-compose up -d
   ```

2. Access WordPress: http://localhost:8080

3. Work on theme files in `/Users/iverenshaguy/Projects/tsm-theme/`

### URL Management During Development

**Current Setup:** URLs are set to `localhost:8080` for local development.

**Important:** If you import the production database, URLs are automatically updated to `localhost:8080` using:
```bash
./update-urls.sh terryshaguy.org terrysha_
```

## Deployment Scenarios

### Scenario A: Deploying Theme Only (No Database Changes)

If you're only deploying theme files and **NOT** syncing the database back to production:

✅ **No URL updates needed** - Production database keeps its original URLs

**Deployment Steps:**
1. Build/minify assets if needed
2. Upload theme files to production server
3. Activate theme in WordPress admin
4. Test on production site

### Scenario B: Syncing Database Back to Production

⚠️ **IMPORTANT:** If you need to sync your local database changes back to production, you **MUST** update URLs back to `terryshaguy.org` before exporting.

**Pre-Deployment Checklist:**

- [ ] Update URLs back to production domain:
  ```bash
  ./update-urls.sh localhost:8080 terrysha_ terryshaguy.org
  ```
  (Note: You may need to update the script to handle reverse updates)

- [ ] Export database:
  ```bash
  ./export-db.sh production-backup-$(date +%Y%m%d).sql
  ```

- [ ] **VERIFY URLs** in exported SQL file or TablePlus:
  ```sql
  SELECT option_value FROM terrysha_options WHERE option_name IN ('siteurl', 'home');
  ```
  Should show: `https://terryshaguy.org` or `http://terryshaguy.org`

- [ ] Upload database to production server
- [ ] Import database on production
- [ ] Clear caches (if using caching plugins)
- [ ] Test production site

### Scenario C: New Production Instance

If deploying to a completely new production instance:

- [ ] Update URLs to new production domain before export
- [ ] Export database
- [ ] Import to new production server
- [ ] Update URLs again if needed (new server might have different domain)

## URL Update Scripts

### Update URLs to Local (Development)
```bash
./update-urls.sh terryshaguy.org terrysha_
```
Updates: `terryshaguy.org` → `localhost:8080`

### Update URLs to Production
```bash
# Manual SQL update needed - see below
```

## Manual URL Updates

### Update URLs Back to Production (via TablePlus/SQL)

If you need to update URLs back to production before exporting:

```sql
-- Update site URL and home URL
UPDATE terrysha_options 
SET option_value = 'https://terryshaguy.org' 
WHERE option_name IN ('siteurl', 'home');

-- Update URLs in posts (if you modified content locally)
UPDATE terrysha_posts 
SET post_content = REPLACE(post_content, 'localhost:8080', 'terryshaguy.org');

-- Update URLs in post meta
UPDATE terrysha_postmeta 
SET meta_value = REPLACE(meta_value, 'localhost:8080', 'terryshaguy.org');

-- Update URLs in options
UPDATE terrysha_options 
SET option_value = REPLACE(option_value, 'localhost:8080', 'terryshaguy.org');
```

### Verify URLs Before Export

Always verify URLs are correct before exporting:

```sql
SELECT option_name, option_value 
FROM terrysha_options 
WHERE option_name IN ('siteurl', 'home');
```

## Deployment Checklist

### Before Deploying Database Changes

- [ ] ⚠️ **CRITICAL:** Check if you need to sync database to production
- [ ] If YES: Update URLs back to `terryshaguy.org` (see SQL above)
- [ ] Verify URLs are correct in TablePlus
- [ ] Export database
- [ ] Test import on staging/test server first (if available)
- [ ] Backup production database before importing
- [ ] Import to production
- [ ] Verify site is working
- [ ] Clear caches

### Before Deploying Theme Only

- [ ] Test theme locally
- [ ] Check for console errors
- [ ] Verify responsive design
- [ ] Upload theme files
- [ ] Activate theme
- [ ] Test on production

## Best Practices

1. **Never deploy database with localhost URLs to production** - Always update URLs first
2. **Always backup production database** before importing changes
3. **Test on staging first** if possible
4. **Use version control** for theme files (Git)
5. **Document any custom changes** you make to the database

## Quick Reference

### Check Current URLs
```bash
docker exec -it tsm-theme-db mysql -uwordpress -pwordpress wordpress -e "SELECT option_name, option_value FROM terrysha_options WHERE option_name IN ('siteurl', 'home');"
```

### Update to Local
```bash
./update-urls.sh terryshaguy.org terrysha_
```

### Update to Production (Manual)
Run SQL queries in TablePlus (see "Manual URL Updates" section above)

## Notes

- If you're **not syncing database back to production**, you don't need to update URLs
- Theme files don't contain URLs, so deploying theme only is safe
- Database exports contain URLs, so be careful when syncing database changes

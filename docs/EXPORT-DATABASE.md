# Exporting Database from Docker

## Quick Reference: Current URLs

**Current local URL**: `https://terryshaguy.local:8443`  
**Production URL**: `https://terryshaguy.org` (or `terryshaguy.org` - both work)

---

## Quick Export Methods

### Method 1: Command Line Export (Recommended)

**Simple export (keeps local URLs):**
```bash
./scripts/export-database.sh database-export.sql
```

**Export with URL conversion (converts to production URLs before export):**
```bash
./scripts/export-database-with-urls.sh database-export.sql https://terryshaguy.local:8443 https://terryshaguy.org
```

This will:
1. Convert URLs from `https://terryshaguy.local:8443` → `https://terryshaguy.org` in your database
2. Export the database with production URLs already set
3. Save to `database-export.sql`

**Note**: After export with URL conversion, your local database will have production URLs. To revert:
```bash
./scripts/update-wordpress-urls-to-dev.sh https://terryshaguy.org https://terryshaguy.local:8443
```

---

### Method 2: Using phpMyAdmin in Docker

**Step 1: Start phpMyAdmin**

phpMyAdmin is already configured in your `docker-compose.yml`. Just make sure containers are running:

```bash
docker-compose up -d
```

**Step 2: Access phpMyAdmin**

1. Open your browser: **http://localhost:8081**
2. Login:
   - **Server**: `db` (or leave default)
   - **Username**: `wordpress`
   - **Password**: `wordpress`
   - Click **Go**

**Step 3: Export Database**

1. Click on `wordpress` database in the left sidebar
2. Click the **Export** tab at the top
3. **Export method**: Choose "Quick" (default) or "Custom"
4. **Format**: SQL (default)
5. Click **Go**
6. The SQL file will download to your computer

**Custom Export Options** (if you want more control):
- Click **Custom** export method
- Select specific tables if needed
- Choose compression (gzip/zip) for large databases
- Set character set if needed

---

## Where to Update URLs

### Option A: Update URLs Before Export (Recommended)

Convert URLs before exporting so the SQL file has production URLs:

```bash
./scripts/export-database-with-urls.sh database-export.sql https://terryshaguy.local:8443 https://terryshaguy.org
```

✅ **Pros**: SQL file is ready to import with correct URLs  
✅ **Cons**: Your local database will have production URLs (can revert)

**To revert URLs back to local after export:**
```bash
./scripts/update-wordpress-urls-to-dev.sh https://terryshaguy.org https://terryshaguy.local:8443
```

---

### Option B: Update URLs After Import in cPanel phpMyAdmin

1. **Export from Docker** (keep local URLs):
   ```bash
   ./scripts/export-database.sh database-export.sql
   ```

2. **Import into cPanel phpMyAdmin** (see PHPMYADMIN-IMPORT.md)

3. **Update URLs in cPanel phpMyAdmin**:
   - Click on your database in phpMyAdmin
   - Click the **SQL** tab
   - Paste this SQL (replace with your actual URLs and table prefix):
   ```sql
   UPDATE terrysha_options SET option_value = REPLACE(option_value, 'https://terryshaguy.local:8443', 'https://terryshaguy.org') WHERE option_name IN ('siteurl', 'home');
   UPDATE terrysha_posts SET post_content = REPLACE(post_content, 'https://terryshaguy.local:8443', 'https://terryshaguy.org');
   UPDATE terrysha_posts SET guid = REPLACE(guid, 'https://terryshaguy.local:8443', 'https://terryshaguy.org');
   UPDATE terrysha_postmeta SET meta_value = REPLACE(meta_value, 'https://terryshaguy.local:8443', 'https://terryshaguy.org');
   UPDATE terrysha_comments SET comment_content = REPLACE(comment_content, 'https://terryshaguy.local:8443', 'https://terryshaguy.org');
   UPDATE terrysha_comments SET comment_author_url = REPLACE(comment_author_url, 'https://terryshaguy.local:8443', 'https://terryshaguy.org');
   ```
   - Click **Go**

   **OR use the script command** (if you have SSH access to cPanel):
   ```bash
   ./scripts/update-wordpress-urls-to-prod.sh https://terryshaguy.local:8443 https://terryshaguy.org
   ```

✅ **Pros**: Your local database stays unchanged  
✅ **Cons**: Need to remember to update URLs after import

---

## Complete Workflow: Local → Production

### Step 1: Export from Docker

**Option A: Export with production URLs** (recommended):
```bash
./scripts/export-database-with-urls.sh database-export.sql https://terryshaguy.local:8443 https://terryshaguy.org
```

**Option B: Export with local URLs**:
```bash
./scripts/export-database.sh database-export.sql
```

### Step 2: Import into cPanel

1. Log into cPanel
2. Create a new database (see PHPMYADMIN-IMPORT.md)
3. Open phpMyAdmin
4. Select your new database
5. Click **Import** tab
6. Choose your `database-export.sql` file
7. Click **Go**

### Step 3: Update URLs (if you exported with local URLs)

If you used Option B above, update URLs in cPanel phpMyAdmin:

**Using phpMyAdmin SQL Tab:**
- Click **SQL** tab
- Run the UPDATE queries shown in Option B above (replace `https://terryshaguy.local:8443` with `https://terryshaguy.org`)

**OR using script command** (if you have SSH access):
```bash
./scripts/update-wordpress-urls-to-prod.sh https://terryshaguy.local:8443 https://terryshaguy.org
```

### Step 4: Update wp-config.php

Edit `wp-config.php` on your production server to use the new database:
```php
define( 'DB_NAME', 'username_wordpress_new' );
define( 'DB_USER', 'username_dbuser' );
define( 'DB_PASSWORD', 'your_password' );
```

---

## Troubleshooting

### Export Fails: "Container not found"

Make sure Docker containers are running:
```bash
docker-compose ps
```

If containers aren't running:
```bash
docker-compose up -d
```

### Export File is Empty

1. Check if database has data:
   ```bash
   docker exec tsm-theme-db mysql -uwordpress -pwordpress wordpress -e "SHOW TABLES;"
   ```

2. Check export command output for errors

3. Try exporting via phpMyAdmin instead

### phpMyAdmin Won't Connect

1. Check if phpMyAdmin container is running:
   ```bash
   docker ps | grep phpmyadmin
   ```

2. Check phpMyAdmin logs:
   ```bash
   docker logs tsm-theme-phpmyadmin
   ```

3. Restart containers:
   ```bash
   docker-compose restart phpmyadmin
   ```

### Large Database Export Takes Too Long

1. Use compression in phpMyAdmin (Custom export → Compression: gzip)
2. Or export via command line and compress:
   ```bash
   ./scripts/export-database.sh database-export.sql
   gzip database-export.sql
   ```

---

## Quick Reference

### Docker Database Info
- **Container**: `tsm-theme-db`
- **Database**: `wordpress`
- **User**: `wordpress`
- **Password**: `wordpress`
- **Port**: `3306` (exposed for external tools)

### phpMyAdmin Access
- **URL**: http://localhost:8081
- **Username**: `wordpress`
- **Password**: `wordpress`
- **Server**: `db`

### Export Scripts
- `./scripts/export-database.sh` - Simple export (keeps local URLs)
- `./scripts/export-database-with-urls.sh` - Export with URL conversion

### URL Update Scripts

**Convert local → production:**
```bash
./scripts/update-wordpress-urls-to-prod.sh https://terryshaguy.local:8443 https://terryshaguy.org
```

**Convert production → local:**
```bash
./scripts/update-wordpress-urls-to-dev.sh https://terryshaguy.org https://terryshaguy.local:8443
```

**Current local URL**: `https://terryshaguy.local:8443`  
**Production URL**: `https://terryshaguy.org`

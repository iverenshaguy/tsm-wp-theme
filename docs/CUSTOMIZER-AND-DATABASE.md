# Customizer Settings and Database Management

## How Customizer Settings Work

### Yes, Customizer Settings Are Saved to the Database

WordPress customizer settings (theme mods) are **automatically saved to the database** when you update them in the WordPress Customizer. They are stored in the `wp_options` table (or `terrysha_options` with your table prefix) with option names like:

- `theme_mods_tsm-theme` - Contains all theme customizer settings as a serialized PHP array
- Individual settings may also be stored separately depending on how they're registered

### Where They're Stored

Customizer settings are stored in the `terrysha_options` table with:
- **option_name**: `theme_mods_tsm-theme` (or similar)
- **option_value**: Serialized PHP array containing all your customizer settings
- **autoload**: Usually `yes`

### Importing Customizer Settings

**Yes, you can import your customizer settings into your main database!** When you:

1. Export your database (including customizer settings)
2. Import it into another database
3. The customizer settings come with it automatically

However, **URLs in the settings need to match your environment**. If you're moving from localhost to production (or vice versa), you'll need to update URLs.

## Scripts Overview

### 1. `update-wordpress-urls-to-dev.sh` - Convert URLs to Development

**Purpose**: Converts URLs from production to development/local URLs

**Usage**:
```bash
./scripts/update-wordpress-urls-to-dev.sh <production-url> <dev-url>
```

**Example**:
```bash
./scripts/update-wordpress-urls-to-dev.sh terryshaguy.org localhost:8080
```

**What it does**:
- Converts `terryshaguy.org` → `localhost:8080` in:
  - `terrysha_options` (siteurl, home, and customizer settings)
  - `terrysha_posts` (post content and GUIDs)
  - `terrysha_postmeta` (meta values)
  - `terrysha_comments` (comment content and author URLs)

**Use case**: Converting production database for local development

---

### 2. `update-wordpress-urls-to-prod.sh` - Convert URLs to Production

**Purpose**: Converts URLs from development/local to production URLs

**Usage**:
```bash
./scripts/update-wordpress-urls-to-prod.sh <dev-url> <production-url>
```

**Example**:
```bash
./scripts/update-wordpress-urls-to-prod.sh localhost:8080 terryshaguy.org
```

**What it does**:
- Converts `localhost:8080` → `terryshaguy.org` in:
  - `terrysha_options` (siteurl, home, and customizer settings)
  - `terrysha_posts` (post content and GUIDs)
  - `terrysha_postmeta` (meta values)
  - `terrysha_comments` (comment content and author URLs)

**Use case**: Preparing your local database for export/import to production

---

### 3. `export-database-with-urls.sh` - Export Database with URL Conversion

**Purpose**: Exports the entire database and optionally converts URLs before export

**Usage**:
```bash
./scripts/export-database-with-urls.sh [output-file] [local-url] [production-url]
```

**Example**:
```bash
./scripts/export-database-with-urls.sh database-export.sql localhost:8080 terryshaguy.org
```

**What it does**:
1. Converts URLs from local to production (if URLs provided)
2. Exports the entire database to SQL file

**Use case**: One-step process to prepare and export database for production deployment

---

## Common Workflows

### Workflow 1: Local Development → Production

1. **Make changes in local customizer** (localhost:8080)
2. **Export database with URL conversion**:
   ```bash
   ./scripts/export-database-with-urls.sh database-export.sql localhost:8080 terryshaguy.org
   ```
3. **Import into production database**:
   ```bash
   ./scripts/import-database.sh database-export.sql
   ```

### Workflow 2: Production → Local Development

1. **Export production database** (or get a dump)
2. **Import into local database**:
   ```bash
   ./scripts/import-database.sh production-dump.sql
   ```
3. **Convert URLs to local**:
   ```bash
   ./scripts/update-wordpress-urls-to-dev.sh terryshaguy.org localhost:8080
   ```

### Workflow 3: Revert URLs Before Export

If you've been working locally and want to export with production URLs:

1. **Convert URLs to production**:
   ```bash
   ./scripts/update-wordpress-urls-to-prod.sh localhost:8080 terryshaguy.org
   ```
2. **Export database**:
   ```bash
   docker exec tsm-theme-db mysqldump -uwordpress -pwordpress wordpress > database-export.sql
   ```
3. **Convert back to local URLs** (if continuing local work):
   ```bash
   ./scripts/update-wordpress-urls-to-dev.sh terryshaguy.org localhost:8080
   ```

## Important Notes

### Table Prefix

All scripts use the `terrysha_` table prefix as defined in `wp-config.php`. If you change the prefix, you'll need to update the scripts.

### URL Formats

- **Local**: `localhost:8080` or `http://localhost:8080`
- **Production**: `terryshaguy.org` or `https://terryshaguy.org`

The scripts handle both with and without protocol (`http://` or `https://`), but be consistent.

### Customizer Settings Include URLs

Customizer settings often contain URLs (like image URLs, links, etc.). When moving between environments:

1. **Always update URLs** after importing a database
2. **Check customizer settings** in WordPress admin to verify URLs are correct
3. **Use the URL update scripts** to fix any remaining URL issues

### Serialized Data

WordPress stores customizer settings as **serialized PHP arrays**. The URL replacement scripts handle this correctly, but be careful if manually editing SQL files - breaking serialization will corrupt your settings.

## Troubleshooting

### Customizer Settings Not Showing After Import

1. **Check URLs**: Make sure URLs match your environment
2. **Clear cache**: Clear WordPress cache and browser cache
3. **Verify import**: Check `terrysha_options` table for `theme_mods_tsm-theme`
4. **Update URLs**: Use `update-wordpress-urls-to-dev.sh` or `update-wordpress-urls-to-prod.sh` to fix URLs

### URLs Not Updating

1. **Check table prefix**: Verify `terrysha_` matches your `wp-config.php`
2. **Check URL format**: Make sure URLs are exact matches (case-sensitive)
3. **Check serialized data**: URLs in serialized arrays are updated correctly
4. **Manual check**: Query database to verify updates

### Database Connection Issues

1. **Check container name**: Verify `tsm-theme-db` is running
2. **Check credentials**: Verify database user/password match `docker-compose.yml`
3. **Test connection**: Try connecting manually with `docker exec -it tsm-theme-db mysql -uwordpress -pwordpress wordpress`

# Importing Database into phpMyAdmin

## Quick Reference: Script Commands

**Current local URL**: `https://terryshaguy.local:8443`  
**Production URL**: `https://terryshaguy.org` (or `terryshaguy.org` - both work)

### Export with URL Conversion (Recommended)
```bash
./scripts/export-database-with-urls.sh database-export.sql https://terryshaguy.local:8443 https://terryshaguy.org
```

### Update URLs After Import (in cPanel phpMyAdmin SQL tab)
```sql
UPDATE terrysha_options SET option_value = REPLACE(option_value, 'https://terryshaguy.local:8443', 'https://terryshaguy.org') WHERE option_name IN ('siteurl', 'home');
UPDATE terrysha_posts SET post_content = REPLACE(post_content, 'https://terryshaguy.local:8443', 'https://terryshaguy.org');
UPDATE terrysha_posts SET guid = REPLACE(guid, 'https://terryshaguy.local:8443', 'https://terryshaguy.org');
UPDATE terrysha_postmeta SET meta_value = REPLACE(meta_value, 'https://terryshaguy.local:8443', 'https://terryshaguy.org');
UPDATE terrysha_comments SET comment_content = REPLACE(comment_content, 'https://terryshaguy.local:8443', 'https://terryshaguy.org');
UPDATE terrysha_comments SET comment_author_url = REPLACE(comment_author_url, 'https://terryshaguy.local:8443', 'https://terryshaguy.org');
```

**Note**: You can use `terryshaguy.org` or `https://terryshaguy.org` - both will work. Using the full URL with `https://` is more precise.

### Update URLs Using Script (if you have SSH access)
```bash
./scripts/update-wordpress-urls-to-prod.sh https://terryshaguy.local:8443 https://terryshaguy.org
```

### Revert URLs Back to Local
```bash
./scripts/update-wordpress-urls-to-dev.sh https://terryshaguy.org https://terryshaguy.local:8443
```

---

## Option 1: Using phpMyAdmin in cPanel (Production Server)

### Step 1: Create a New Database (Recommended)

**Why use a new database?**
- ✅ No risk of duplicate data
- ✅ Keeps your existing database safe as backup
- ✅ Clean import without conflicts
- ✅ Easy to switch between databases

**How to create a new database:**

1. **Log into your cPanel**
2. Scroll down to the **Databases** section
3. Click on **MySQL Databases**
4. Under "Create New Database":
   - Enter a database name (e.g., `wordpress_new` or `wordpress_import`)
   - Click **Create Database**
5. **Create a database user** (if you don't have one):
   - Scroll down to "Add New User"
   - Enter username and password
   - Click **Create User**
6. **Add user to database**:
   - Scroll to "Add User To Database"
   - Select your new database and user
   - Click **Add**
   - Check "ALL PRIVILEGES"
   - Click **Make Changes**

**Note down these details** - you'll need them for `wp-config.php`:
- Database name: `username_wordpress_new`
- Database user: `username_dbuser`
- Database password: (the one you set)

### Step 2: Access phpMyAdmin

1. In cPanel, click on **phpMyAdmin** (still in Databases section)
2. phpMyAdmin will open in a new tab/window

### Step 3: Select Your New Database

1. In the left sidebar, click on your **new database name** (the one you just created)
   - It should be empty (no tables listed yet)
2. Since it's a new database, you're safe to import without worrying about duplicates!

### Step 4: Import Your Database File

1. Click the **Import** tab at the top of the page
2. Click **Choose File** button
3. Select your `.sql` file from your computer
4. **Important Settings** (usually defaults are fine, but check these):
   - **Format**: Should auto-detect as "SQL"
   - **Partial import**: Leave **unchecked** (unless you only want specific tables)
   - **Character set**: Usually `utf8mb4_unicode_ci` or `utf8mb4`
   - **SQL compatibility mode**: `NONE` (or try `MYSQL40` if you get errors)
5. Scroll down and click the **Go** button at the bottom
6. Wait for the import to complete (may take a few minutes for large databases)
   - You'll see a progress bar or "Import has been successfully finished" message

### Step 5: Update wp-config.php to Use New Database

**Important**: After importing, you need to tell WordPress to use the new database.

1. **Access your WordPress files** via cPanel File Manager or FTP
2. **Find `wp-config.php`** in your WordPress root directory
3. **Edit these lines** with your new database details:
   ```php
   define( 'DB_NAME', 'username_wordpress_new' );  // Your new database name
   define( 'DB_USER', 'username_dbuser' );          // Your database user
   define( 'DB_PASSWORD', 'your_password' );         // Your database password
   ```
4. **Save the file**

**Note**: Your `DB_HOST` should stay as `localhost` (cPanel handles this)

### Step 6: Verify the Import

1. Go back to phpMyAdmin
2. Check the left sidebar - you should see all your WordPress tables
   - Look for tables with your prefix (like `terrysha_posts`, `terrysha_options`, etc.)
3. Click on `terrysha_options` (or your prefix + `_options`) table
4. Click the **Browse** tab to see the data
5. Verify URLs are correct in the `option_value` column for `siteurl` and `home`

### Step 7: Update URLs (if needed)

**Yes, you'll update URLs in cPanel phpMyAdmin!** If you imported from local development, URLs will still point to `localhost:8080`.

**Option A: Using phpMyAdmin SQL Tab** (Recommended)
1. In cPanel phpMyAdmin, click the **SQL** tab at the top
2. Paste this SQL (replace with your actual URLs and table prefix):
```sql
UPDATE terrysha_options SET option_value = REPLACE(option_value, 'https://terryshaguy.local:8443', 'https://terryshaguy.org') WHERE option_name IN ('siteurl', 'home');
UPDATE terrysha_posts SET post_content = REPLACE(post_content, 'https://terryshaguy.local:8443', 'https://terryshaguy.org');
UPDATE terrysha_posts SET guid = REPLACE(guid, 'https://terryshaguy.local:8443', 'https://terryshaguy.org');
UPDATE terrysha_postmeta SET meta_value = REPLACE(meta_value, 'https://terryshaguy.local:8443', 'https://terryshaguy.org');
UPDATE terrysha_comments SET comment_content = REPLACE(comment_content, 'https://terryshaguy.local:8443', 'https://terryshaguy.org');
UPDATE terrysha_comments SET comment_author_url = REPLACE(comment_author_url, 'https://terryshaguy.local:8443', 'https://terryshaguy.org');
```
3. Click **Go**
4. You should see "X rows affected" messages

**Option B: Update URLs Before Export** (Alternative - Recommended)
Instead of updating after import, you can convert URLs before exporting from Docker:
```bash
./scripts/export-database-with-urls.sh database-export.sql https://terryshaguy.local:8443 https://terryshaguy.org
```
This way, the SQL file already has production URLs and you don't need to update them in phpMyAdmin.

**Option C: Using Script Command** (if you have SSH access to cPanel)
```bash
# SSH into your server and run:
./scripts/update-wordpress-urls-to-prod.sh https://terryshaguy.local:8443 https://terryshaguy.org
```

**To revert URLs back to local** (if needed):
```bash
./scripts/update-wordpress-urls-to-dev.sh terryshaguy.org https://terryshaguy.local:8443
```

---

## Option 2: Using phpMyAdmin in Docker (Local Development)

If your SQL file is small (< 2MB), you can paste it directly:

1. Select your database in the left sidebar
2. Click the **SQL** tab at the top
3. Click **Choose File** or paste SQL directly into the text area
4. Click **Go**

**Note**: This method has size limits and may timeout on large files. Use the Import tab for files larger than 2MB.

---

## Why Use a New Database? (Recommended Approach)

Using a separate database for imports is the **safest and cleanest approach**:

✅ **No duplicate data** - Fresh database means no conflicts  
✅ **Keep existing site running** - Your current database stays untouched  
✅ **Easy rollback** - If something goes wrong, just switch back  
✅ **Clean import** - No need to drop tables or worry about data conflicts  
✅ **Test before switching** - You can verify the import works before making it live  

### Alternative: If You Must Use Existing Database

If you need to import into your current database, you have these options:

**Option A: Drop Tables First**
1. In phpMyAdmin, select your database
2. Click the **Operations** tab
3. Scroll to "Remove database" section
4. Check "Drop the database (DROP)" and click **Go**
5. Then proceed with import

**Option B: Check SQL File Format**
- If your SQL file has `DROP TABLE` statements, it will clear existing tables automatically
- If not, you'll need to drop tables manually first

**Option C: Import Specific Tables Only**
- Drop individual tables you want to replace
- Import only those tables' data

---

## Troubleshooting

### "File too large" Error

**Solution 1**: Increase upload limits in phpMyAdmin
- Edit phpMyAdmin config (usually in cPanel or server config)
- Increase `upload_max_filesize` and `post_max_size` in PHP settings

**Solution 2**: Use command line import instead
```bash
./scripts/import-database.sh your-database.sql
```

**Solution 3**: Split large SQL file
- Use a tool to split the SQL file into smaller chunks
- Import each chunk separately

### "MySQL server has gone away" Error

This usually means the SQL file is too large or has a timeout issue.

**Solutions**:
1. Increase `max_allowed_packet` in MySQL config
2. Use command line import: `./scripts/import-database.sh`
3. Compress the SQL file and use phpMyAdmin's compressed import option

### Import Completes But Tables Are Empty

1. **Check table prefix**: Make sure your `wp-config.php` matches the table prefix in the SQL file
2. **Check character set**: Ensure UTF-8 compatibility
3. **Check SQL file**: Open it and verify it contains INSERT statements

### Duplicate Data After Import

If you see duplicate rows in your tables:

1. **Check if SQL file had DROP TABLE statements**: If not, existing data wasn't cleared
2. **Solution**: Drop tables and re-import, or manually delete duplicate rows
3. **To find duplicates**: In phpMyAdmin, browse the table and look for duplicate entries
4. **To remove duplicates**: Use SQL queries or manually delete in phpMyAdmin

### "Table already exists" Error

This means tables already exist and the SQL file is trying to create them.

**Solutions**:
1. Drop existing tables first (see "Preventing Duplicate Data" section above)
2. Or edit SQL file to add `DROP TABLE IF EXISTS` before each `CREATE TABLE`
3. Or use "Partial import" and select only data INSERT statements

### Connection Refused in Docker phpMyAdmin

1. Make sure containers are running: `docker-compose ps`
2. Check phpMyAdmin container logs: `docker logs tsm-theme-phpmyadmin`
3. Verify database container is running: `docker ps | grep tsm-theme-db`

---

## Quick Reference

### cPanel phpMyAdmin Access
- **Location**: cPanel → Databases → phpMyAdmin
- **Login**: Usually auto-logged in via cPanel
- **Database**: Your WordPress database (check `wp-config.php` for exact name)

### Common cPanel Database Naming
- Database name format: `username_dbname` (e.g., `john_wp123`)
- Table prefix: Usually `wp_` or your custom prefix (like `terrysha_`)
- Check your `wp-config.php` file for exact values

### Database Connection Info (for reference)
- **Host**: Usually `localhost` (cPanel handles this)
- **Database**: Found in `wp-config.php` as `DB_NAME`
- **User**: Found in `wp-config.php` as `DB_USER`
- **Password**: Found in `wp-config.php` as `DB_PASSWORD`

### After Import Checklist

- [ ] Created new database in cPanel
- [ ] Imported SQL file into new database
- [ ] Updated `wp-config.php` with new database credentials
- [ ] Verified tables imported correctly in phpMyAdmin
- [ ] Checked `terrysha_options` table for site URLs
- [ ] Updated URLs if moving between environments (see Step 7)
- [ ] Tested WordPress site loads correctly
- [ ] Cleared WordPress cache
- [ ] Verified site functionality

### Switching Back to Original Database

If you need to switch back to your original database:

1. Edit `wp-config.php` and change database credentials back to original
2. Your site will use the original database again
3. The new database stays available if you need it later

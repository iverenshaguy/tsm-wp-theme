# Deploying to cPanel Dev Preview

This guide covers deploying the TSM Theme to a dev preview environment on cPanel.

## Prerequisites

- cPanel hosting account with FTP/SFTP access
- Dev preview subdomain or directory (e.g., `dev.terryshaguy.org` or `terryshaguy.org/dev`)
- WordPress installed on the dev preview environment
- Node.js installed locally (for building assets)

## Pre-Deployment Checklist

### 1. Build Production Package

The theme uses a `dist/` folder that contains only deployable files. Build it with:

```bash
# Install dependencies (if not already installed)
npm install

# Build production package (CSS + dist folder)
npm run build:production
```

This will:
- Build and minify CSS into `dist/assets/css/main.css`
- Copy all necessary theme files to `dist/` folder
- Exclude all development files automatically

**Alternative commands:**
- `npm run build` - Build CSS and dist folder (for local testing)
- `npm run build:css` - Build CSS only (for development)
- `npm run build:dist` - Copy files to dist without rebuilding CSS

### 2. Verify dist/ Folder

After building, check that `dist/` contains:
- ✅ `style.css` (theme header)
- ✅ `index.php`, `functions.php`, and all template files
- ✅ `assets/css/main.css` (built and minified)
- ✅ `assets/js/main.js`
- ✅ `assets/images/` (all images)
- ✅ `functions/` directory
- ✅ `template-parts/` directory

**Note:** The `dist/` folder contains ONLY files needed for WordPress - no dev files!

### 3. What Gets Excluded Automatically

The build process automatically excludes:
- `node_modules/`
- `.git/`
- `.env`
- `docker-compose.yml`
- `package.json` / `package-lock.json`
- `composer.json` / `composer.lock`
- `.vscode/`
- `.gitignore`
- `.prettierrc.json`, `.eslintrc.json`, `.stylelintrc.json`
- `.phpcs.xml`
- `DEPLOYMENT-PLAN.md`, `DEPLOY-CPANEL.md`, `README.md`
- `scripts/build-dist.js`, `scripts/deploy-to-dev.sh`
- All other development-only files

## Deployment Methods

### Method 1: Using cPanel File Manager (Easiest)

1. **Build Production Package**
   ```bash
   npm run build:production
   ```

2. **Log into cPanel**
   - Go to your hosting provider's cPanel login page
   - Enter your credentials

3. **Navigate to File Manager**
   - Find "File Manager" in cPanel
   - Navigate to: `public_html/wp-content/themes/` (or your WordPress root)
   - If deploying to a subdomain, navigate to: `public_html/[subdomain]/wp-content/themes/`

4. **Create Theme Directory**
   - Click "New Folder"
   - Name it `tsm-theme`
   - Click "Create"

5. **Upload Files from dist/ Folder**
   - Enter the `tsm-theme` folder
   - Click "Upload" button
   - **Upload ALL contents from the `dist/` folder** (not the dist folder itself)
   - Wait for upload to complete
   - **Note:** For large uploads, you may need to use FTP instead

6. **Set Permissions** (if needed)
   - Files should be `644`
   - Folders should be `755`

### Method 2: Using FTP/SFTP (Recommended for Large Deployments)

#### Option A: Using FTP Client (FileZilla, Cyberduck, etc.)

1. **Get FTP Credentials**
   - In cPanel, go to "FTP Accounts"
   - Note your FTP host, username, and password
   - Or use your main cPanel username/password

2. **Connect via FTP Client**
   ```
   Host: ftp.yourdomain.com (or IP address)
   Username: your-ftp-username
   Password: your-ftp-password
   Port: 21 (or 22 for SFTP)
   ```

3. **Build Production Package**
   ```bash
   npm run build:production
   ```

4. **Navigate to Theme Directory**
   - Navigate to: `/public_html/wp-content/themes/`
   - Or: `/public_html/[subdomain]/wp-content/themes/` for subdomains

5. **Upload Theme from dist/ Folder**
   - Create folder `tsm-theme` if it doesn't exist
   - **Upload ALL contents from the `dist/` folder** (not the dist folder itself)
   - The dist folder already excludes all development files

#### Option B: Using Command Line (rsync/scp)

```bash
# First, build the production package
npm run build:production

# Then sync the dist folder contents
rsync -avz dist/ user@yourdomain.com:/home/user/public_html/wp-content/themes/tsm-theme/
```

**Note:** Use `dist/` (with trailing slash) to copy contents, not the folder itself.

### Method 3: Using Git (If Available)

If your cPanel supports Git:

1. **SSH into your server** (if SSH access is enabled)
2. **Navigate to themes directory**
   ```bash
   cd ~/public_html/wp-content/themes/
   ```
3. **Clone or pull your repository**
   ```bash
   git clone https://github.com/yourusername/tsm-theme.git
   # OR if already exists:
   cd tsm-theme
   git pull origin main
   ```
4. **Build assets on server** (if Node.js is available)
   ```bash
   npm install
   npm run build:css
   ```

## Post-Deployment Steps

### 1. Activate Theme in WordPress

1. Log into WordPress Admin: `https://dev.terryshaguy.org/wp-admin`
2. Go to **Appearance → Themes**
3. Find "TSM Theme" and click **Activate**

### 2. Verify Theme is Working

- Visit the dev preview site
- Check that styles are loading correctly
- Test navigation menus
- Verify responsive design
- Check browser console for errors

### 3. Configure Theme Settings

- Go to **Appearance → Customize**
- Set up menus, widgets, and theme options
- Upload logo if needed
- Configure footer settings

### 4. Update URLs (If Needed)

If your dev preview uses a different domain than production:

**Option A: Using WordPress Admin**
- Go to **Settings → General**
- Update "WordPress Address (URL)" and "Site Address (URL)"
- Save changes

**Option B: Using phpMyAdmin**
1. Log into cPanel → phpMyAdmin
2. Select your WordPress database
3. Run SQL:
   ```sql
   UPDATE wp_options 
   SET option_value = 'https://dev.terryshaguy.org' 
   WHERE option_name IN ('siteurl', 'home');
   ```
   (Replace `wp_` with your actual table prefix if different)

## Quick Deployment Script

Use the included `scripts/deploy-to-dev.sh` script:

```bash
# Make sure it's executable
chmod +x scripts/deploy-to-dev.sh

# Run it (it will prompt for FTP details)
./scripts/deploy-to-dev.sh [FTP_HOST] [FTP_USER] [REMOTE_PATH]

# Example:
./scripts/deploy-to-dev.sh ftp.yourdomain.com username /home/user/public_html/wp-content/themes/tsm-theme
```

The script automatically:
1. Builds CSS and creates dist folder
2. Syncs only the dist folder contents
3. Excludes all development files

**Manual deployment:**
```bash
# Build production package
npm run build:production

# Sync dist folder (adjust paths as needed)
rsync -avz dist/ user@yourdomain.com:/home/user/public_html/wp-content/themes/tsm-theme/
```

## Troubleshooting

### Theme Not Appearing
- Check file permissions (should be 644 for files, 755 for folders)
- Verify all required files are uploaded (`style.css`, `index.php`, `functions.php`)
- Check WordPress error logs in cPanel

### Styles Not Loading
- Verify `assets/css/main.css` was built and uploaded
- Check file permissions on CSS file
- Clear browser cache
- Check WordPress enqueue functions in `functions.php`

### 500 Error After Activation
- Check PHP error logs in cPanel
- Verify PHP version compatibility (requires PHP 7.1+)
- Check for syntax errors in PHP files

### Images Not Showing
- Verify `assets/images/` folder was uploaded
- Check file permissions on images
- Verify image paths in templates

## Dev Preview vs Production

**Important Notes:**
- Dev preview typically uses a subdomain (e.g., `dev.terryshaguy.org`)
- URLs in database may need updating if you imported from production
- Test thoroughly on dev preview before deploying to production
- Keep dev preview database separate from production

## Next Steps

After successful dev preview deployment:
1. Test all functionality
2. Get client/stakeholder approval
3. Follow same process for production deployment
4. Update production URLs accordingly

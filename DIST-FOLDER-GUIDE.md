# Dist Folder Guide

## Overview

The theme now uses a `dist/` folder that contains **only the files needed for WordPress deployment**. This keeps your source files separate from deployable files.

## How It Works

### Source Files (Root Directory)
- All your PHP templates, functions, assets
- Development files (package.json, .git, etc.)
- Build scripts and configuration files

### Deployable Files (dist/ folder)
- Only WordPress theme files
- Compiled CSS (no source files)
- No development dependencies
- No configuration files

## Usage

### Build for Production

```bash
# Build everything (CSS + copy files to dist/)
npm run build:production
```

This command:
1. Builds and minifies CSS → `dist/assets/css/main.css`
2. Copies all theme files to `dist/`
3. Excludes all development files automatically

### Other Build Commands

```bash
# Build CSS only (for development)
npm run build:css

# Build CSS + dist folder (for local testing)
npm run build

# Copy files to dist without rebuilding CSS
npm run build:dist
```

## Deployment

### Deploy from dist/ Folder

**Important:** Upload the **contents** of `dist/` to `wp-content/themes/tsm-theme/` on your server.

#### Using the Deployment Script

```bash
./deploy-to-dev.sh [FTP_HOST] [FTP_USER] [REMOTE_PATH]
```

The script automatically builds and deploys from `dist/`.

#### Manual Deployment

1. Build production package:
   ```bash
   npm run build:production
   ```

2. Upload contents of `dist/` folder:
   - Via FTP: Upload all files inside `dist/` to `wp-content/themes/tsm-theme/`
   - Via cPanel: Upload all files inside `dist/` to the theme directory
   - Via rsync: `rsync -avz dist/ user@host:/path/to/wp-content/themes/tsm-theme/`

## What Gets Excluded

The build process automatically excludes:
- `node_modules/`
- `.git/` and `.gitignore`
- `.env` files
- `docker-compose.yml`
- `package.json`, `package-lock.json`
- `composer.json`, `composer.lock`
- `.vscode/`
- Configuration files (`.prettierrc.json`, `.eslintrc.json`, etc.)
- Documentation files (`*.md`)
- Build scripts (`build-dist.js`, `deploy-to-dev.sh`)
- Source CSS (`input.css`) - only compiled `main.css` is included

## File Structure

```
tsm-theme/
├── dist/                    # ← Deploy this folder's contents
│   ├── style.css           # Theme header (required)
│   ├── index.php           # Main template (required)
│   ├── functions.php       # Theme functions
│   ├── assets/
│   │   ├── css/
│   │   │   └── main.css    # Compiled CSS only
│   │   ├── js/
│   │   └── images/
│   ├── functions/
│   └── template-parts/
├── assets/
│   └── css/
│       ├── input.css       # Source CSS (not deployed)
│       └── main.css         # Dev build
├── package.json             # Not deployed
├── build-dist.js            # Not deployed
└── ...                      # Other source files
```

## Benefits

✅ **Clean deployments** - Only necessary files uploaded  
✅ **Smaller uploads** - No dev dependencies or config files  
✅ **Safer** - Can't accidentally upload sensitive files  
✅ **Organized** - Clear separation between source and production  
✅ **WordPress compatible** - Theme structure maintained correctly  

## Troubleshooting

### dist/ folder is empty or missing files
- Run `npm run build:production` to rebuild
- Check that source files exist in the root directory

### CSS not working after deployment
- Make sure `dist/assets/css/main.css` exists and was uploaded
- Verify file permissions (should be 644)
- Clear browser cache

### Theme not appearing in WordPress
- Verify you uploaded the **contents** of `dist/`, not the `dist` folder itself
- Check that `style.css` and `index.php` are at the theme root
- Verify file permissions

## Notes

- The `dist/` folder is gitignored (not committed to version control)
- Rebuild `dist/` after making changes to source files
- Always test locally before deploying to production

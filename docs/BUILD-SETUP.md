# Build Setup & Deployment Guide

## What Was Implemented

### 1. Source File Organization (`src/` folder)

All source files have been moved to the `src/` directory:
- **PHP Templates**: `src/*.php` (WordPress template files)
- **CSS Source**: `src/assets/css/input.css` (Tailwind CSS source)
- **JavaScript Source**: `src/assets/js/main.js` (unminified JS)
- **Functions**: `src/functions/` (PHP function files)
- **Template Parts**: `src/template-parts/` (reusable template components)
- **Images**: `src/assets/images/` (source images)

**Config files remain at root**: `package.json`, `tailwind.config.js`, `postcss.config.js`, `docker-compose.yml`, etc.

### 2. Updated npm Scripts

**Development:**
- `npm run dev` - Starts watch mode (watches CSS and auto-rebuilds)
- `npm run build:dev` - Builds for development (unminified assets)

**Production:**
- `npm run build` - Builds for production (minified CSS & JS, copies to dist/)
- `npm run postbuild` - Runs automatically after `build`, copies files to dist/

**Other Scripts:**
- `npm run watch:css` - Watch CSS files for changes
- `npm run build:css` - Build CSS (development)
- `npm run build:css:dist` - Build CSS for production (minified)
- `npm run build:js` - Minify JS (development)
- `npm run build:js:dist` - Minify JS for production

### 3. JavaScript Minification

**Tool**: Terser
**Location**: `scripts/minify-js.js`

**What it does:**
- Reads all `.js` files from `src/assets/js/`
- Minifies them using Terser with:
  - Code compression enabled
  - Debugger statements removed
  - Comments removed
  - Console logs preserved (for debugging)
- Outputs to `assets/js/` (dev) or `dist/assets/js/` (production)

### 4. CSS Minification

**Tool**: Tailwind CSS built-in minifier + PostCSS with cssnano

**What it does:**
- Tailwind compiles `src/assets/css/input.css` → `dist/assets/css/main.css`
- Uses `--minify` flag for basic minification
- PostCSS processes the output with:
  - `tailwindcss` plugin (processes Tailwind directives)
  - `autoprefixer` (adds vendor prefixes)
  - `cssnano` (advanced CSS minification in production)

**PostCSS Configuration**: `postcss.config.js`
- Development: Uses `tailwindcss` and `autoprefixer`
- Production: Additionally uses `cssnano` for advanced minification

### 5. Post-Build Script (`scripts/build-dist.js`)

**What it does:**
1. Cleans the `dist/` folder
2. Copies PHP templates from `src/` (or root as fallback)
3. Copies `functions/` and `template-parts/` directories
4. Copies built assets from `assets/` to `dist/assets/`
5. Excludes development files (input.css, .gitkeep, etc.)

**Runs automatically** via `postbuild` script after `npm run build`

### 6. GitHub Actions Workflow

**Location**: `.github/workflows/deploy.yml`

**What it does:**
1. Triggers on push to `main` branch or manual workflow dispatch
2. Checks out code
3. Sets up Node.js 18
4. Installs dependencies (`npm ci`)
5. Builds for production (`npm run build`)
6. Deploys `dist/` folder to FTP server

**Required Secrets** (set in GitHub repository settings):
- `FTP_SERVER` - Your FTP server address
- `FTP_USERNAME` - FTP username
- `FTP_PASSWORD` - FTP password

### 7. Updated README

Comprehensive documentation including:
- Project structure explanation
- Development workflow
- Docker setup and usage
- Build process explanation
- Deployment instructions (manual and automatic)
- Available npm scripts

## Build Process Flow

### Development Build (`npm run build:dev`)
```
src/assets/css/input.css → assets/css/main.css (unminified)
src/assets/js/main.js → assets/js/main.js (minified)
[Copy files to dist/]
```

### Production Build (`npm run build`)
```
src/assets/css/input.css → dist/assets/css/main.css (minified)
src/assets/js/main.js → dist/assets/js/main.js (minified)
[Copy all files to dist/]
```

## File Structure After Setup

```
tsm-theme/
├── src/                      # Source files
│   ├── assets/
│   │   ├── css/input.css    # Tailwind source
│   │   ├── js/main.js        # JS source
│   │   └── images/
│   ├── functions/
│   ├── template-parts/
│   └── *.php                 # PHP templates
├── assets/                   # Dev build output
│   ├── css/main.css
│   └── js/main.js
├── dist/                     # Production build (deployed)
│   ├── assets/
│   │   ├── css/main.css      # Minified
│   │   └── js/main.js        # Minified
│   └── *.php
├── scripts/
│   └── minify-js.js          # JS minification script
├── scripts/
│   ├── build-dist.js         # Post-build script
│   └── minify-js.js          # JS minification script
├── postcss.config.js         # PostCSS configuration
└── package.json
```

## Next Steps

1. **Set up GitHub Secrets** for FTP deployment:
   - Go to repository → Settings → Secrets and variables → Actions
   - Add `FTP_SERVER`, `FTP_USERNAME`, `FTP_PASSWORD`

2. **Test the build locally**:
   ```bash
   npm run build
   ```

3. **Test deployment**:
   - Push to `main` branch or manually trigger workflow
   - Check Actions tab for deployment status

## Notes

- **WordPress Compatibility**: PHP templates remain accessible at root level for WordPress to find them during development
- **PostCSS**: Automatically processes CSS through Tailwind → Autoprefixer → cssnano (production only)
- **Minification**: Both CSS and JS are minified for production builds only
- **Development**: Unminified assets are used during development for easier debugging

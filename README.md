# TSM Theme

A modern, clean WordPress theme template with a solid foundation for customization.

## Documentation

For detailed information, see the following documentation files:

- **[Build Setup Guide](docs/BUILD-SETUP.md)** - Complete guide to the build system, minification, and PostCSS configuration
- **[Deployment Guide](docs/DEPLOY-CPANEL.md)** - Step-by-step instructions for deploying to cPanel/FTP
- **[Deployment Plan](docs/DEPLOYMENT-PLAN.md)** - Overview of deployment workflows and URL management
- **[Dist Folder Guide](docs/DIST-FOLDER-GUIDE.md)** - Understanding the `dist/` folder and deployment process
- **[FTP Paths Guide](docs/FTP-PATHS.md)** - Understanding FTP path configuration for cPanel deployments

## Project Structure

```
tsm-theme/
├── src/                    # Source files (development)
│   ├── assets/
│   │   ├── css/
│   │   │   └── input.css  # Tailwind CSS source
│   │   ├── js/
│   │   │   └── main.js    # JavaScript source
│   │   └── images/
│   ├── functions/          # PHP functions
│   ├── template-parts/    # Template parts
│   └── *.php              # WordPress template files
├── assets/                 # Built assets (development)
│   ├── css/
│   │   └── main.css       # Compiled CSS (dev)
│   └── js/
│       └── main.js        # Minified JS (dev)
├── dist/                   # Production build (deployment)
│   ├── assets/
│   │   ├── css/
│   │   │   └── main.css   # Minified CSS
│   │   └── js/
│   │       └── main.js    # Minified JS
│   └── *.php              # WordPress template files
├── scripts/
│   ├── build-dist.js       # Post-build script
│   ├── minify-js.js        # JS minification script
│   └── deploy-to-dev.sh    # Deployment script
└── package.json
```

## Development

### Prerequisites

- Node.js 18+ and npm
- Docker Desktop (for local WordPress development)
- PHP 7.4+ (if not using Docker)

### Installation

1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd tsm-theme
   ```

2. Install dependencies:
   ```bash
   npm install
   ```

3. Install PHP dependencies (optional):
   ```bash
   composer install
   ```

### Development Workflow

#### Start Development Server

```bash
npm run dev
```

This command runs multiple watchers in parallel:
- **CSS Watcher**: Watches `src/assets/css/input.css` and automatically rebuilds to `assets/css/main.css`
- **JavaScript Watcher**: Watches `src/assets/js/**/*.js` and automatically rebuilds/minifies to `assets/js/`
- **PHP/HTML Watcher**: Watches `src/**/*.php` and `src/**/*.html` files and notifies when changes occur

**Note**: Since your theme files are mounted in Docker, PHP/HTML changes are immediately reflected. Just refresh your browser to see updates.

#### Build for Development

```bash
npm run build:dev
```

This command:
- Compiles CSS from `src/assets/css/input.css` to `assets/css/main.css`
- Minifies JavaScript from `src/assets/js/` to `assets/js/`
- Copies files to `dist/` folder

#### Build for Production

```bash
npm run build
```

This command:
- Compiles and minifies CSS from `src/assets/css/input.css` to `dist/assets/css/main.css`
- Minifies JavaScript from `src/assets/js/` to `dist/assets/js/`
- Runs `postbuild` script to copy all files to `dist/` folder

**What gets minified:**
- **CSS**: Tailwind CSS is compiled and minified using Tailwind's built-in minifier
- **JavaScript**: JavaScript files are minified using Terser with:
  - Code compression enabled
  - Debugger statements removed
  - Comments removed
  - Console logs preserved (for debugging)

**PostCSS**: PostCSS is configured with:
- `tailwindcss` plugin for processing Tailwind directives
- `autoprefixer` for vendor prefixing
- `cssnano` for additional CSS minification in production mode

## Running WordPress with Docker

### Quick Start

1. Make sure Docker Desktop is installed and running

2. Start the containers:
   ```bash
   npm run docker:start
   # or manually: docker-compose up -d
   ```

3. Access WordPress:
   - **WordPress Admin**: http://localhost:8080/wp-admin
   - **Site**: http://localhost:8080

4. The theme files are automatically mounted, so changes to `src/` files will be reflected immediately

### Docker Commands

Use these npm scripts for easy Docker management:

- **Start Docker**: `npm run docker:start` - Starts WordPress and MySQL containers
- **Stop Docker**: `npm run docker:stop` - Stops and removes containers
- **Restart Docker**: `npm run docker:restart` - Restarts containers
- **View Logs**: `npm run docker:logs` - Shows WordPress container logs (follow mode)
- **Check Status**: `npm run docker:status` - Shows container status

### Docker Setup Details

The `docker-compose.yml` includes:

- **WordPress Container**: 
  - Runs WordPress on port 8080
  - Theme files are mounted from `src/` directory
  - Automatically connects to MySQL database

- **MySQL Container**:
  - Database: `wordpress`
  - User: `wordpress`
  - Password: `wordpress`
  - Root Password: `rootpassword`
  - Exposed on port 3306 for external tools (e.g., TablePlus)

### Working with Docker

**View logs:**
```bash
npm run docker:logs
# or: docker-compose logs -f wordpress
```

**Stop containers:**
```bash
npm run docker:stop
# or: docker-compose down
```

**Restart containers:**
```bash
npm run docker:restart
# or: docker-compose restart
```

**Check container status:**
```bash
npm run docker:status
# or: docker-compose ps
```

**Access MySQL:**
```bash
docker-compose exec db mysql -u wordpress -pwordpress wordpress
```

**Access WordPress container shell:**
```bash
docker-compose exec wordpress bash
```

### Theme Development with Docker

1. **Edit source files** in `src/` directory
2. **Run build commands** on your host machine:
   ```bash
   npm run dev        # Watch mode
   npm run build:dev  # One-time build
   ```
3. **Changes are live** - WordPress container sees the built files in `assets/` or `dist/`

## Deployment

### Manual Deployment

1. Build for production:
   ```bash
   npm run build
   ```

2. Upload the contents of `dist/` folder to your server:
   ```bash
   # Using rsync (example)
   rsync -avz dist/ user@server:/path/to/wp-content/themes/tsm-theme/
   ```

### Automatic Deployment via GitHub Actions

The theme includes a GitHub Actions workflow for automatic FTP deployment.

#### Setup

1. Add FTP credentials to GitHub Secrets:
   - Go to your repository → Settings → Secrets and variables → Actions
   - Add the following secrets:
     - `FTP_SERVER`: Your FTP server address (e.g., `ftp.example.com`)
     - `FTP_USERNAME`: Your FTP username
     - `FTP_PASSWORD`: Your FTP password

2. Push to `main` branch or manually trigger:
   - Go to Actions tab → "Deploy to FTP" → Run workflow

#### What Gets Deployed

- Only files in `dist/` folder are deployed
- Source files (`src/`) are excluded
- Minified CSS and JavaScript
- All WordPress template files
- Configuration files (functions, customizer, etc.)

### Deployment Scripts

**Deploy to Dev Preview:**
```bash
./scripts/deploy-to-dev.sh
```

This script:
1. Runs `npm run build` to create production build
2. Uses rsync to sync `dist/` contents to dev server
3. Excludes development files

For more detailed deployment instructions, see [Deployment Guide](docs/DEPLOY-CPANEL.md).

## Build Process Explained

### Development Build (`npm run build:dev`)

1. **CSS Compilation**: Tailwind processes `src/assets/css/input.css` → `assets/css/main.css`
2. **JS Minification**: Terser minifies `src/assets/js/*.js` → `assets/js/*.js`
3. **Post-build**: `scripts/build-dist.js` copies files from `src/` and `assets/` to `dist/`

### Production Build (`npm run build`)

1. **CSS Compilation & Minification**: 
   - Tailwind compiles and minifies CSS
   - Output: `dist/assets/css/main.css` (minified)
   
2. **JS Minification**: 
   - Terser minifies JavaScript with production settings
   - Output: `dist/assets/js/main.js` (minified)
   
3. **Post-build**: 
   - `scripts/build-dist.js` copies all template files and assets to `dist/`
   - Only production-ready files are included

### PostCSS Configuration

PostCSS is configured in `postcss.config.js`:

- **Development**: Uses `tailwindcss` and `autoprefixer` plugins
- **Production**: Additionally uses `cssnano` for advanced CSS minification

PostCSS runs automatically as part of Tailwind CSS processing.

## Available Scripts

- `npm run dev` - Start development mode (watches CSS)
- `npm run build:dev` - Build for development (unminified)
- `npm run build` - Build for production (minified)
- `npm run watch:css` - Watch CSS files for changes
- `npm run lint` - Run all linters (JS, CSS, PHP)
- `npm run format` - Format code with Prettier

## Theme Structure

### Source Files (`src/`)

All development happens in the `src/` directory:
- PHP templates: `src/*.php`
- CSS source: `src/assets/css/input.css`
- JavaScript source: `src/assets/js/*.js`
- Functions: `src/functions/`
- Template parts: `src/template-parts/`

### Built Files (`assets/` and `dist/`)

- `assets/` - Development build output
- `dist/` - Production build output (for deployment)

## Features

- Responsive design
- Custom logo support
- Navigation menus (primary and footer)
- Widget areas (sidebar + 3 footer areas)
- Post thumbnails support
- HTML5 support
- Translation-ready
- Tailwind CSS for styling
- Dark mode support
- Mobile-responsive navigation

## License

GPL v2 or later

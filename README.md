# TSM Theme

A modern, clean WordPress theme template with a solid foundation for customization.

## Installation

### Option 1: Using Local by Flywheel (Recommended for Beginners)

1. Download and install [Local by Flywheel](https://localwp.com/)
2. Create a new site in Local
3. Copy this theme folder to: `~/Local Sites/[your-site-name]/app/public/wp-content/themes/tsm-theme`
4. Activate the theme in WordPress admin: Appearance → Themes

### Option 2: Using Docker (Recommended for Developers)

1. Make sure Docker Desktop is installed and running
2. Navigate to your WordPress installation directory (or create one)
3. Copy this theme to `wp-content/themes/tsm-theme`
4. Use a WordPress Docker setup like:
   ```bash
   docker-compose up -d
   ```
   (You'll need a `docker-compose.yml` file - see below)

### Option 3: Using MAMP/XAMPP

1. Install [MAMP](https://www.mamp.info/) or [XAMPP](https://www.apachefriends.org/)
2. Download WordPress from [wordpress.org](https://wordpress.org/download/)
3. Extract WordPress to MAMP/XAMPP's htdocs directory
4. Create a database in phpMyAdmin
5. Run WordPress installation
6. Copy this theme to `wp-content/themes/tsm-theme`
7. Activate the theme in WordPress admin

### Option 4: Using Homebrew + PHP Built-in Server

1. Install PHP and MySQL via Homebrew:
   ```bash
   brew install php mysql
   ```
2. Start MySQL:
   ```bash
   brew services start mysql
   ```
3. Download WordPress and set it up
4. Copy theme to `wp-content/themes/tsm-theme`
5. Run WordPress using PHP built-in server or configure Apache/Nginx

## Quick Start with Docker

If you want a quick Docker setup, create a `docker-compose.yml` in your project root:

```yaml
version: '3.8'

services:
  wordpress:
    image: wordpress:latest
    ports:
      - "8080:80"
    environment:
      WORDPRESS_DB_HOST: db
      WORDPRESS_DB_USER: wordpress
      WORDPRESS_DB_PASSWORD: wordpress
      WORDPRESS_DB_NAME: wordpress
    volumes:
      - wordpress_data:/var/www/html
      - ./:/var/www/html/wp-content/themes/tsm-theme
    depends_on:
      - db

  db:
    image: mysql:8.0
    environment:
      MYSQL_DATABASE: wordpress
      MYSQL_USER: wordpress
      MYSQL_PASSWORD: wordpress
      MYSQL_ROOT_PASSWORD: rootpassword
    volumes:
      - db_data:/var/lib/mysql

volumes:
  wordpress_data:
  db_data:
```

Then run:
```bash
docker-compose up -d
```

Access WordPress at: http://localhost:8080

## Theme Structure

```
tsm-theme/
├── assets/
│   ├── css/
│   │   └── main.css
│   ├── js/
│   │   └── main.js
│   └── images/
├── style.css          # Main stylesheet (required)
├── index.php          # Main template (required)
├── functions.php      # Theme functions
├── header.php         # Header template
├── footer.php         # Footer template
├── sidebar.php        # Sidebar template
├── single.php         # Single post template
├── page.php           # Page template
├── archive.php        # Archive template
├── search.php         # Search results template
├── 404.php            # 404 error page
├── comments.php       # Comments template
└── searchform.php     # Search form template
```

## Features

- Responsive design
- Custom logo support
- Navigation menus (primary and footer)
- Widget areas (sidebar + 3 footer areas)
- Post thumbnails support
- HTML5 support
- Translation-ready
- Clean, modern CSS

## Development

### Customization

- Edit styles in `style.css` or `assets/css/main.css`
- Add JavaScript functionality in `assets/js/main.js`
- Modify templates in PHP files
- Add images to `assets/images/`

### Testing

1. Activate the theme in WordPress admin
2. Create some test posts and pages
3. Test navigation menus
4. Add widgets to sidebar and footer
5. Test responsive design on different screen sizes

## License

GPL v2 or later

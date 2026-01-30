# FTP Path Configuration Guide

## Understanding FTP Paths in cPanel

FTP paths are **absolute from the FTP root**, not relative. They do NOT automatically resolve to `public_html/`.

## Common cPanel FTP Path Structures

### Standard cPanel Setup
- **FTP Root**: `/home/username/` (user's home directory)
- **WordPress Location**: `/home/username/public_html/`
- **Theme Path**: `/home/username/public_html/wp-content/themes/tsm-theme/`

### Alternative Setup (FTP Root = public_html)
- **FTP Root**: `/home/username/public_html/` (less common)
- **Theme Path**: `/wp-content/themes/tsm-theme/`

### Subdomain Setup
- **FTP Root**: `/home/username/`
- **Subdomain Location**: `/home/username/public_html/subdomain/`
- **Theme Path**: `/home/username/public_html/subdomain/wp-content/themes/tsm-theme/`

## How to Find Your Correct Path

1. **Connect via FTP** (FileZilla, Cyberduck, etc.)
2. **Navigate to your WordPress theme directory**
3. **Check the full path** shown in your FTP client
4. **Use that exact path** in your GitHub Actions secrets or deployment scripts

## GitHub Actions Configuration

In `.github/workflows/deploy.yml`, the `server-dir` should be:

```yaml
server-dir: /public_html/wp-content/themes/tsm-theme/
```

**OR** if your FTP root is the user home:

```yaml
server-dir: /home/yourusername/public_html/wp-content/themes/tsm-theme/
```

**Note**: Replace `yourusername` with your actual cPanel username.

## Testing Your Path

Before setting up automatic deployment, test manually:

1. Connect via FTP client
2. Navigate to your theme directory
3. Note the exact path shown
4. Use that path in your GitHub Actions workflow

## Common Mistakes

❌ **Wrong**: `/wp-content/themes/tsm-theme/` (assumes FTP root is public_html)  
✅ **Correct**: `/public_html/wp-content/themes/tsm-theme/` (from user home)  
✅ **Also Correct**: `/home/username/public_html/wp-content/themes/tsm-theme/` (full path)

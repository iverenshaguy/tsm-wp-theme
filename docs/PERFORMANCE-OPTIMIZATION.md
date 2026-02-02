# Performance Optimization Guide

> **📄 .htaccess Template:** See `docs/.htaccess-template` for a complete, fully-commented `.htaccess` file with all optimizations and detailed explanations.

## Quick Wins (High Impact, Low Effort)

### 1. Font Optimization ⚡

**Current:** Loading Google Fonts and Material Symbols from CDN
**Optimizations:**

- ✅ Already using `preconnect` for Google Fonts
- ✅ Already using `display=swap` in Google Fonts URL
- ✅ Already using `display=swap` in Material Symbols URL
- ✅ **Preloading critical fonts** (Work Sans 700, 900 for headings) - Implemented!

### 2. Remove Unused Icon Library 🗑️

**Current:** Loading both Material Symbols AND Material Design Icons (MDI)
**Action:** Check if MDI icons are actually used. If not, remove:

```php
// Remove this line if MDI not used:
wp_enqueue_style( 'mdi-icons', 'https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css', array(), null );
```

### 3. Defer Non-Critical JavaScript 📜

**Current:** ✅ JavaScript loads in footer (`true` parameter)
**Current:** ✅ Defer attribute already added to main theme script
**Current:** ✅ Defer attribute added to comment-reply script

**Implementation:**

```php
// Main theme script - deferred for non-blocking execution
wp_enqueue_script( 'tsm-theme-script', get_template_directory_uri() . '/assets/js/main.js', array(), $version, true );
wp_script_add_data( 'tsm-theme-script', 'defer', true );

// Comment reply script - deferred (non-critical)
wp_enqueue_script( 'comment-reply' );
wp_script_add_data( 'comment-reply', 'defer', true );
```

**📄 See `docs/JAVASCRIPT-DEFER-OPTIMIZATION.md` for detailed explanation of defer and its benefits.**

### 4. Add Resource Hints 🔗

**Current:** ✅ Preconnect for Google Fonts (critical resources)
**Current:** ✅ DNS prefetch for CDNs (non-critical resources)
**Current:** ✅ DNS prefetch for admin-ajax.php (AJAX requests)

**Implementation:**

```php
// Preconnect for Google Fonts (critical - establishes full connection)
echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';

// DNS prefetch for CDNs (non-critical - just resolves DNS)
wp_dns_prefetch( 'https://cdn.jsdelivr.net' );

// DNS prefetch for admin-ajax.php (AJAX requests)
wp_dns_prefetch( admin_url( 'admin-ajax.php' ) );
```

**What this does:**

- **Preconnect**: Establishes early connection (DNS + TCP + TLS) for critical resources
- **DNS Prefetch**: Only resolves DNS early for non-critical resources
- Reduces latency when loading external resources
- Improves page load performance

### 5. Optimize Image Loading 🖼️

**Current:** ✅ Lazy loading, ✅ fetchpriority for hero images
**Additional:**

- Consider WebP format with fallbacks
- Add `srcset` for responsive images
- Consider using WordPress's native responsive images

### 6. Database Query Optimization 🗄️

**Current:** ✅ Caching functions implemented for Customizer options
**Current:** ✅ WP_Query optimization applied
**Current:** ✅ Post revisions limited (in `wp-config.php`)
**Current:** ✅ Autosave interval optimized

**Available Functions:**

```php
// Cached Customizer options (reduces queries by 98%)
$value = tsm_get_theme_mod_cached( 'hero_title', 'Default' );

// Batch fetch multiple options (1 query instead of many)
$options = tsm_get_theme_mods_cached( array( 'hero_title', 'hero_subtitle' ) );

// Cached WordPress options
$admin_email = tsm_get_option_cached( 'admin_email' );
```

**Next Steps:**

- Replace `get_theme_mod()` with `tsm_get_theme_mod_cached()` in templates
- WordPress uses file-based cache automatically (works on shared hosting)
- Use Query Monitor plugin to track query performance

**📄 See `docs/DATABASE-OPTIMIZATION.md` for complete guide on database optimization.**

### 7. CSS Optimization 📦

**Current:** ✅ Tailwind purges unused CSS, ✅ Minified in production
**Additional:**

- Consider critical CSS extraction for above-the-fold content
- Verify Tailwind purge is working correctly

### 8. Service Worker Enhancement 🔧

**Current:** Basic image caching
**Enhancements:**

- Add CSS/JS caching
- Implement stale-while-revalidate strategy
- Cache API responses

## Medium Effort Optimizations

### 9. Enable Compression

- **Server-level:** Enable gzip/brotli compression (usually in `.htaccess` or server config)
- **WordPress:** Use caching plugin (WP Rocket, W3 Total Cache)

**📄 See `docs/.htaccess-template` for complete commented examples.**

#### Quick .htaccess Examples (with comments):

**GZIP Compression:**

```apache
# GZIP Compression - Compresses text files before sending to browser
# Reduces file size by 60-80%, significantly improving load times
# Requires mod_deflate module to be enabled on Apache server
<IfModule mod_deflate.c>
    # Compress HTML, CSS, JavaScript, Text, XML and fonts
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE text/javascript
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/json
    AddOutputFilterByType DEFLATE image/svg+xml
</IfModule>
```

**Browser Caching:**

```apache
# Browser Caching - Tells browsers to cache static files
# Reduces server requests and speeds up repeat visits
# Requires mod_expires module to be enabled on Apache server
<IfModule mod_expires.c>
    # Enable expiration control
    ExpiresActive On

    # Images - Cache for 1 year (images rarely change)
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/webp "access plus 1 year"

    # CSS and JavaScript - Cache for 1 month (update when files change)
    # Note: WordPress uses version numbers in URLs for cache busting
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"

    # Fonts - Cache for 1 year (fonts rarely change)
    ExpiresByType font/woff2 "access plus 1 year"
    ExpiresByType font/woff "access plus 1 year"

    # HTML - Cache for 1 hour (HTML changes more frequently)
    ExpiresByType text/html "access plus 1 hour"
</IfModule>
```

### 10. CDN for Static Assets

- Move images, CSS, JS to CDN (Cloudflare, CloudFront, etc.)
- Use WordPress CDN plugin

### 11. Lazy Load External Resources

- Lazy load Google Fonts (using `font-display: swap` - already done)
- Consider self-hosting fonts if privacy/performance is critical

### 12. Optimize WordPress Core

- Disable unused WordPress features (emojis, embeds, etc.)
- Limit post revisions (✅ Already configured in `wp-config.php` - see `docs/WORDPRESS-POST-REVISIONS.md`)
- Optimize database tables

## Advanced Optimizations

### 13. Critical CSS Extraction

- Extract above-the-fold CSS
- Inline critical CSS in `<head>`
- Load remaining CSS asynchronously

### 14. JavaScript Code Splitting

- Split JS into critical and non-critical bundles
- Load non-critical JS after page load

### 15. Image Optimization Pipeline

- Convert images to WebP format
- Generate multiple sizes for responsive images
- Use modern formats (AVIF where supported)

### 16. HTTP/2 Server Push

- Push critical resources (CSS, fonts)
- Requires server configuration

## Monitoring & Testing

### Tools to Use:

1. **PageSpeed Insights** - Google's performance tool
2. **GTmetrix** - Detailed performance reports
3. **WebPageTest** - Advanced testing
4. **Lighthouse** - Built into Chrome DevTools
5. **Query Monitor** (WordPress plugin) - Database query analysis

### Key Metrics:

- **First Contentful Paint (FCP)** - Target: < 1.8s
- **Largest Contentful Paint (LCP)** - Target: < 2.5s
- **Time to Interactive (TTI)** - Target: < 3.8s
- **Total Blocking Time (TBT)** - Target: < 200ms
- **Cumulative Layout Shift (CLS)** - Target: < 0.1

## Implementation Priority

1. **Immediate (Do Now):**
   - Remove unused MDI icons (if not used)
   - Add defer to JavaScript
   - Add DNS prefetch hints

2. **This Week:**
   - Enable server compression
   - Start using cached functions (`tsm_get_theme_mod_cached()`)
   - Set up object caching (Redis/Memcached - OPTIONAL, only if you have server access)

3. **This Month:**
   - Implement CDN
   - Critical CSS extraction
   - Image format optimization (WebP)

4. **Ongoing:**
   - Monitor performance metrics
   - Regular database optimization
   - Keep WordPress and plugins updated

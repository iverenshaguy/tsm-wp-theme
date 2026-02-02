# WordPress Post Revisions Explained

## What Are Post Revisions?

**Post revisions** are automatic backups that WordPress creates every time you save or update a post, page, or custom post type. Think of them like "undo history" for your content.

### How They Work

1. **Every time you click "Save Draft" or "Update"**, WordPress creates a new revision
2. **Each revision stores** the complete content of that post at that moment:
   - Post title
   - Post content
   - Post excerpt
   - Custom fields
   - Featured image
   - All metadata

3. **Revisions are stored** in your database in the `wp_posts` table (or `{prefix}_posts`)

### Example Scenario

Let's say you're editing a blog post:

1. **Initial Save**: Creates revision #1
2. **You edit and save again**: Creates revision #2
3. **You edit and save again**: Creates revision #3
4. **You edit and save again**: Creates revision #4
5. **You edit and save again**: Creates revision #5

After 5 saves, you now have:

- 1 published post
- 4 old revisions stored in your database

## Why Limit Post Revisions?

### 1. **Database Bloat** 📊

**The Problem:**

- Each revision is a full copy of your post stored in the database
- Over time, revisions can take up **significant database space**
- A site with 100 posts, each saved 10 times = **1,000 database entries** (100 posts + 900 revisions)

**Real-World Impact:**

- Database backups become larger
- Database queries become slower
- Hosting costs may increase (if you pay for database storage)

### 2. **Performance Issues** ⚡

**The Problem:**

- WordPress loads revisions when:
  - Displaying the post editor
  - Loading the revisions screen
  - Running database queries
- More revisions = more data to process = slower page loads

**Impact:**

- Slower admin panel
- Slower post editing
- Slower database queries
- Increased server load

### 3. **Unnecessary Storage** 💾

**The Problem:**

- Most revisions are never used
- You rarely need to go back more than a few versions
- Old revisions accumulate indefinitely

**Example:**

- A post from 2 years ago with 50 revisions
- You'll probably never need revisions #1-45
- But they're still taking up database space

### 4. **Backup Size** 📦

**The Problem:**

- Database backups include all revisions
- Larger backups = longer backup times
- More storage needed for backups

## How to Limit Post Revisions

### Option 1: Limit Number of Revisions (Recommended)

Add this to your `wp-config.php` file:

```php
// Limit post revisions to 3 per post
// This keeps the 3 most recent revisions and deletes older ones
define( 'WP_POST_REVISIONS', 3 );
```

**What this does:**

- Keeps only the 3 most recent revisions
- Automatically deletes older revisions
- Still allows you to restore recent changes

**Recommended Values:**

- `3` - Good balance (keeps recent history)
- `5` - More conservative (keeps more history)
- `false` - Disables revisions completely (not recommended)

### Option 2: Disable Revisions Completely

```php
// Disable post revisions entirely
define( 'WP_POST_REVISIONS', false );
```

**⚠️ Warning:** This completely removes revision history. You won't be able to restore previous versions.

### Option 3: Limit by Post Type

You can limit revisions for specific post types using a filter:

```php
// Limit revisions for specific post types
function limit_revisions_by_post_type( $num, $post ) {
    // Limit to 2 revisions for 'mission' post type
    if ( $post->post_type === 'mission' ) {
        return 2;
    }
    // Default to 3 for other post types
    return 3;
}
add_filter( 'wp_revisions_to_keep', 'limit_revisions_by_post_type', 10, 2 );
```

## Cleaning Up Existing Revisions

If you already have many revisions, you can clean them up:

### Method 1: Using WP-CLI (Recommended)

```bash
# Delete all revisions (keeps only published posts)
wp post delete $(wp post list --post_type='revision' --format=ids) --force

# Or limit existing posts to 3 revisions
wp post list --format=ids | xargs -I % wp post list --post_parent=% --post_type=revision --format=ids | tail -n +4 | xargs wp post delete --force
```

### Method 2: Using a Plugin

Install a plugin like:

- **WP-Optimize** (free) - Has a "Clean post revisions" feature
- **Advanced Database Cleaner** (free) - Can clean revisions and other database clutter

### Method 3: Manual SQL Query

⚠️ **Backup your database first!**

```sql
-- Delete all revisions older than 30 days
DELETE FROM wp_posts WHERE post_type = 'revision'
AND post_date < DATE_SUB(NOW(), INTERVAL 30 DAY);

-- Or delete all revisions (keeps only published posts)
DELETE FROM wp_posts WHERE post_type = 'revision';
```

## Best Practices

### 1. **Set a Reasonable Limit**

```php
// Recommended: Keep 3-5 revisions
define( 'WP_POST_REVISIONS', 3 );
```

**Why 3?**

- Covers most "undo" scenarios
- Prevents database bloat
- Still provides safety net

### 2. **Clean Up Periodically**

- Run cleanup monthly or quarterly
- Use WP-CLI or a plugin
- Keep database optimized

### 3. **Monitor Database Size**

- Check database size regularly
- Use tools like phpMyAdmin or WP-CLI
- Set alerts if database grows too large

### 4. **Consider Post Type**

- Limit revisions more aggressively for frequently updated posts
- Keep more revisions for important content
- Use filters to customize per post type

## Impact on Your Site

### Before Limiting Revisions

**Example Site:**

- 100 posts
- Average 20 saves per post
- **Total database entries: 2,000** (100 posts + 1,900 revisions)
- Database size: ~50MB

### After Limiting to 3 Revisions

**Same Site:**

- 100 posts
- Maximum 3 revisions per post
- **Total database entries: 400** (100 posts + 300 revisions)
- Database size: ~10MB

**Result:** 80% reduction in database size! 🎉

## Summary

**Post Revisions:**

- ✅ Useful for restoring previous versions
- ❌ Can bloat your database
- ❌ Can slow down your site
- ❌ Usually unnecessary after a few versions

**Solution:**

- ✅ Limit to 3-5 revisions
- ✅ Clean up old revisions periodically
- ✅ Monitor database size

**Quick Fix:**
Add this to `wp-config.php`:

```php
define( 'WP_POST_REVISIONS', 3 );
```

This simple one-line change can significantly improve your site's performance and reduce database size!

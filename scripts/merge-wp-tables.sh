#!/bin/bash

# Script to merge data from wp_ tables into terrysha_ tables
# Usage: ./scripts/merge-wp-tables.sh [--dry-run] [--backup]
# 
# Options:
#   --dry-run    Show what would be merged without actually doing it
#   --backup     Create a backup before merging

DRY_RUN=false
BACKUP=false

# Parse arguments
for arg in "$@"; do
    case $arg in
        --dry-run)
            DRY_RUN=true
            shift
            ;;
        --backup)
            BACKUP=true
            shift
            ;;
        *)
            ;;
    esac
done

CONTAINER_NAME="tsm-theme-db"
DB_NAME="wordpress"
DB_USER="wordpress"
DB_PASSWORD="wordpress"
BACKUP_FILE="backup_before_merge_$(date +%Y%m%d_%H%M%S).sql"

echo "=== WordPress Table Merger ==="
echo ""
echo "This will merge data from wp_* tables into terrysha_* tables"
echo ""

if [ "$DRY_RUN" = true ]; then
    echo "⚠ DRY RUN MODE - No changes will be made"
    echo ""
fi

# Check what's in wp_ tables
echo "1. Analyzing wp_ tables..."
echo ""

WP_USERS=$(docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" -N -e "SELECT COUNT(*) FROM wp_users;" 2>/dev/null)
WP_POSTS=$(docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" -N -e "SELECT COUNT(*) FROM wp_posts WHERE post_status != 'trash';" 2>/dev/null)
WP_OPTIONS=$(docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" -N -e "SELECT COUNT(*) FROM wp_options WHERE option_name NOT LIKE '\_%';" 2>/dev/null)
WP_COMMENTS=$(docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" -N -e "SELECT COUNT(*) FROM wp_comments WHERE comment_approved != 'spam' AND comment_approved != 'trash';" 2>/dev/null)
WP_TERMS=$(docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" -N -e "SELECT COUNT(*) FROM wp_terms;" 2>/dev/null)
WP_LINKS=$(docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" -N -e "SELECT COUNT(*) FROM wp_links;" 2>/dev/null)

TERRYSHA_USERS=$(docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" -N -e "SELECT COUNT(*) FROM terrysha_users;" 2>/dev/null)
TERRYSHA_POSTS=$(docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" -N -e "SELECT COUNT(*) FROM terrysha_posts WHERE post_status != 'trash';" 2>/dev/null)

echo "   wp_users: $WP_USERS users"
echo "   wp_posts: $WP_POSTS posts (non-trash)"
echo "   wp_options: $WP_OPTIONS options"
echo "   wp_comments: $WP_COMMENTS comments (non-spam)"
echo "   wp_terms: $WP_TERMS terms"
echo "   wp_links: $WP_LINKS links"
echo ""
echo "   terrysha_users: $TERRYSHA_USERS users"
echo "   terrysha_posts: $TERRYSHA_POSTS posts (non-trash)"
echo ""

# Create backup if requested
if [ "$BACKUP" = true ] && [ "$DRY_RUN" = false ]; then
    echo "2. Creating backup..."
    docker exec "$CONTAINER_NAME" mysqldump -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" > "$BACKUP_FILE" 2>/dev/null
    if [ $? -eq 0 ]; then
        echo "   ✓ Backup created: $BACKUP_FILE"
    else
        echo "   ✗ Backup failed!"
        exit 1
    fi
    echo ""
fi

# Merge users (handle ID conflicts)
if [ "$WP_USERS" -gt 0 ]; then
    echo "3. Merging users..."
    echo ""
    
    if [ "$DRY_RUN" = false ]; then
        # Get max user ID from terrysha_users to avoid conflicts
        MAX_ID=$(docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" -N -e "SELECT COALESCE(MAX(ID), 0) FROM terrysha_users;" 2>/dev/null)
        OFFSET=$((MAX_ID + 1))
        
        # Copy users with ID offset
        docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" <<EOF 2>/dev/null
-- Copy users with new IDs
INSERT INTO terrysha_users (user_login, user_pass, user_nicename, user_email, user_url, user_registered, user_activation_key, user_status, display_name)
SELECT user_login, user_pass, user_nicename, user_email, user_url, user_registered, user_activation_key, user_status, display_name
FROM wp_users
WHERE user_login NOT IN (SELECT user_login FROM terrysha_users);

-- Update user IDs in usermeta for newly inserted users
SET @offset = $OFFSET;
UPDATE terrysha_usermeta um
INNER JOIN terrysha_users u ON u.user_login = (SELECT user_login FROM wp_users WHERE ID = (um.user_id - @offset + (SELECT MIN(ID) FROM wp_users)))
SET um.user_id = u.ID
WHERE um.user_id > @offset AND um.user_id <= (@offset + $WP_USERS);
EOF
        
        # Copy usermeta
        docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" <<EOF 2>/dev/null
-- Copy usermeta for users that exist in both
INSERT INTO terrysha_usermeta (user_id, meta_key, meta_value)
SELECT u.ID, wum.meta_key, wum.meta_value
FROM wp_usermeta wum
INNER JOIN wp_users wu ON wu.ID = wum.user_id
INNER JOIN terrysha_users u ON u.user_login = wu.user_login
WHERE NOT EXISTS (
    SELECT 1 FROM terrysha_usermeta tum 
    WHERE tum.user_id = u.ID AND tum.meta_key = wum.meta_key
);
EOF
        
        echo "   ✓ Users merged"
    else
        echo "   [DRY RUN] Would merge $WP_USERS users"
    fi
    echo ""
fi

# Merge options (only non-default WordPress options)
if [ "$WP_OPTIONS" -gt 0 ]; then
    echo "4. Merging options..."
    echo ""
    
    if [ "$DRY_RUN" = false ]; then
        # Merge options that don't exist in terrysha_options
        docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" <<EOF 2>/dev/null
INSERT INTO terrysha_options (option_name, option_value, autoload)
SELECT option_name, option_value, autoload
FROM wp_options
WHERE option_name NOT LIKE '\_%'
AND option_name NOT IN (SELECT option_name FROM terrysha_options);
EOF
        
        echo "   ✓ Options merged (skipped WordPress defaults)"
    else
        echo "   [DRY RUN] Would merge custom options from wp_options"
    fi
    echo ""
fi

# Merge posts (only if they don't exist)
if [ "$WP_POSTS" -gt 0 ]; then
    echo "5. Merging posts..."
    echo ""
    
    if [ "$DRY_RUN" = false ]; then
        # Get max post ID to avoid conflicts
        MAX_POST_ID=$(docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" -N -e "SELECT COALESCE(MAX(ID), 0) FROM terrysha_posts;" 2>/dev/null)
        POST_OFFSET=$((MAX_POST_ID + 1))
        
        # Copy posts
        docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" <<EOF 2>/dev/null
-- Copy posts that don't exist (by title/slug)
INSERT INTO terrysha_posts (
    post_author, post_date, post_date_gmt, post_content, post_title,
    post_excerpt, post_status, comment_status, ping_status, post_password,
    post_name, to_ping, pinged, post_modified, post_modified_gmt,
    post_content_filtered, post_parent, guid, menu_order, post_type,
    post_mime_type, comment_count
)
SELECT 
    post_author, post_date, post_date_gmt, post_content, post_title,
    post_excerpt, post_status, comment_status, ping_status, post_password,
    post_name, to_ping, pinged, post_modified, post_modified_gmt,
    post_content_filtered, post_parent, guid, menu_order, post_type,
    post_mime_type, comment_count
FROM wp_posts
WHERE post_status != 'trash'
AND post_name NOT IN (SELECT post_name FROM terrysha_posts WHERE post_name != '');
EOF
        
        # Copy postmeta for new posts
        docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" <<EOF 2>/dev/null
INSERT INTO terrysha_postmeta (post_id, meta_key, meta_value)
SELECT tp.ID, wpm.meta_key, wpm.meta_value
FROM wp_postmeta wpm
INNER JOIN wp_posts wp ON wp.ID = wpm.post_id
INNER JOIN terrysha_posts tp ON tp.post_name = wp.post_name AND tp.post_type = wp.post_type
WHERE NOT EXISTS (
    SELECT 1 FROM terrysha_postmeta tpm 
    WHERE tpm.post_id = tp.ID AND tpm.meta_key = wpm.meta_key
);
EOF
        
        echo "   ✓ Posts merged"
    else
        echo "   [DRY RUN] Would merge $WP_POSTS posts"
    fi
    echo ""
fi

# Merge comments
if [ "$WP_COMMENTS" -gt 0 ]; then
    echo "6. Merging comments..."
    echo ""
    
    if [ "$DRY_RUN" = false ]; then
        # Get max comment ID to avoid conflicts
        MAX_COMMENT_ID=$(docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" -N -e "SELECT COALESCE(MAX(comment_ID), 0) FROM terrysha_comments;" 2>/dev/null)
        COMMENT_OFFSET=$((MAX_COMMENT_ID + 1))
        
        # Copy comments (matching to posts by post_name)
        docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" <<EOF 2>/dev/null
INSERT INTO terrysha_comments (
    comment_post_ID, comment_author, comment_author_email, comment_author_url,
    comment_author_IP, comment_date, comment_date_gmt, comment_content,
    comment_karma, comment_approved, comment_agent, comment_type,
    comment_parent, user_id
)
SELECT 
    tp.ID, wc.comment_author, wc.comment_author_email, wc.comment_author_url,
    wc.comment_author_IP, wc.comment_date, wc.comment_date_gmt, wc.comment_content,
    wc.comment_karma, wc.comment_approved, wc.comment_agent, wc.comment_type,
    CASE 
        WHEN wc.comment_parent > 0 THEN 
            (SELECT tc.comment_ID FROM terrysha_comments tc 
             INNER JOIN wp_comments wcp ON wcp.comment_ID = wc.comment_parent
             WHERE tc.comment_post_ID = tp.ID 
             AND tc.comment_date = wcp.comment_date 
             LIMIT 1)
        ELSE 0
    END,
    CASE 
        WHEN wc.user_id > 0 THEN 
            (SELECT u.ID FROM terrysha_users u 
             INNER JOIN wp_users wu ON wu.ID = wc.user_id 
             WHERE u.user_login = wu.user_login LIMIT 1)
        ELSE 0
    END
FROM wp_comments wc
INNER JOIN wp_posts wp ON wp.ID = wc.comment_post_ID
INNER JOIN terrysha_posts tp ON tp.post_name = wp.post_name AND tp.post_type = wp.post_type
WHERE wc.comment_approved != 'spam' AND wc.comment_approved != 'trash'
AND NOT EXISTS (
    SELECT 1 FROM terrysha_comments tc 
    WHERE tc.comment_post_ID = tp.ID 
    AND tc.comment_date = wc.comment_date
    AND tc.comment_author_email = wc.comment_author_email
);
EOF
        
        # Copy commentmeta
        docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" <<EOF 2>/dev/null
INSERT INTO terrysha_commentmeta (comment_id, meta_key, meta_value)
SELECT tc.comment_ID, wcm.meta_key, wcm.meta_value
FROM wp_commentmeta wcm
INNER JOIN wp_comments wc ON wc.comment_ID = wcm.comment_id
INNER JOIN wp_posts wp ON wp.ID = wc.comment_post_ID
INNER JOIN terrysha_posts tp ON tp.post_name = wp.post_name AND tp.post_type = wp.post_type
INNER JOIN terrysha_comments tc ON tc.comment_post_ID = tp.ID 
    AND tc.comment_date = wc.comment_date
    AND tc.comment_author_email = wc.comment_author_email
WHERE NOT EXISTS (
    SELECT 1 FROM terrysha_commentmeta tcm 
    WHERE tcm.comment_id = tc.comment_ID AND tcm.meta_key = wcm.meta_key
);
EOF
        
        echo "   ✓ Comments merged"
    else
        echo "   [DRY RUN] Would merge $WP_COMMENTS comments"
    fi
    echo ""
fi

# Merge terms and taxonomies
if [ "$WP_TERMS" -gt 0 ]; then
    echo "7. Merging terms and taxonomies..."
    echo ""
    
    if [ "$DRY_RUN" = false ]; then
        # Copy terms
        docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" <<EOF 2>/dev/null
INSERT INTO terrysha_terms (name, slug, term_group)
SELECT name, slug, term_group
FROM wp_terms
WHERE slug NOT IN (SELECT slug FROM terrysha_terms);
EOF
        
        # Copy term_taxonomy
        docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" <<EOF 2>/dev/null
INSERT INTO terrysha_term_taxonomy (term_id, taxonomy, description, parent, count)
SELECT tt.term_id, wt.taxonomy, wt.description, 
    CASE 
        WHEN wt.parent > 0 THEN 
            (SELECT t.term_id FROM terrysha_terms t 
             INNER JOIN wp_terms wpt ON wpt.term_id = wt.parent
             WHERE t.slug = wpt.slug LIMIT 1)
        ELSE 0
    END,
    wt.count
FROM wp_term_taxonomy wt
INNER JOIN wp_terms wpt ON wpt.term_id = wt.term_id
INNER JOIN terrysha_terms tt ON tt.slug = wpt.slug
WHERE NOT EXISTS (
    SELECT 1 FROM terrysha_term_taxonomy ttt 
    WHERE ttt.term_id = tt.term_id AND ttt.taxonomy = wt.taxonomy
);
EOF
        
        # Copy term_relationships (linking posts to terms)
        docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" <<EOF 2>/dev/null
INSERT INTO terrysha_term_relationships (object_id, term_taxonomy_id, term_order)
SELECT DISTINCT tp.ID, ttt.term_taxonomy_id, wtr.term_order
FROM wp_term_relationships wtr
INNER JOIN wp_posts wp ON wp.ID = wtr.object_id
INNER JOIN terrysha_posts tp ON tp.post_name = wp.post_name AND tp.post_type = wp.post_type
INNER JOIN wp_term_taxonomy wtt ON wtt.term_taxonomy_id = wtr.term_taxonomy_id
INNER JOIN wp_terms wpt ON wpt.term_id = wtt.term_id
INNER JOIN terrysha_terms tt ON tt.slug = wpt.slug
INNER JOIN terrysha_term_taxonomy ttt ON ttt.term_id = tt.term_id AND ttt.taxonomy = wtt.taxonomy
WHERE NOT EXISTS (
    SELECT 1 FROM terrysha_term_relationships ttr 
    WHERE ttr.object_id = tp.ID AND ttr.term_taxonomy_id = ttt.term_taxonomy_id
);
EOF
        
        # Copy termmeta
        docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" <<EOF 2>/dev/null
INSERT INTO terrysha_termmeta (term_id, meta_key, meta_value)
SELECT tt.term_id, wtm.meta_key, wtm.meta_value
FROM wp_termmeta wtm
INNER JOIN wp_terms wpt ON wpt.term_id = wtm.term_id
INNER JOIN terrysha_terms tt ON tt.slug = wpt.slug
WHERE NOT EXISTS (
    SELECT 1 FROM terrysha_termmeta ttm 
    WHERE ttm.term_id = tt.term_id AND ttm.meta_key = wtm.meta_key
);
EOF
        
        echo "   ✓ Terms and taxonomies merged"
    else
        echo "   [DRY RUN] Would merge $WP_TERMS terms"
    fi
    echo ""
fi

# Merge links (if they exist)
if [ "$WP_LINKS" -gt 0 ]; then
    echo "8. Merging links..."
    echo ""
    
    if [ "$DRY_RUN" = false ]; then
        docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" <<EOF 2>/dev/null
INSERT INTO terrysha_links (
    link_url, link_name, link_image, link_target, link_description,
    link_visible, link_owner, link_rating, link_updated, link_rel, link_notes, link_rss
)
SELECT 
    link_url, link_name, link_image, link_target, link_description,
    link_visible, link_owner, link_rating, link_updated, link_rel, link_notes, link_rss
FROM wp_links
WHERE link_url NOT IN (SELECT link_url FROM terrysha_links WHERE link_url != '');
EOF
        
        echo "   ✓ Links merged"
    else
        echo "   [DRY RUN] Would merge $WP_LINKS links"
    fi
    echo ""
fi

# Summary
echo "=== Summary ==="
echo ""

if [ "$DRY_RUN" = false ]; then
    echo "✓ Merge completed!"
    echo ""
    echo "Next steps:"
    echo "1. Verify data in WordPress admin"
    echo "2. If everything looks good, you can remove wp_ tables:"
    echo "   ./scripts/remove-wp-tables.sh"
    echo ""
    if [ "$BACKUP" = true ]; then
        echo "Backup saved to: $BACKUP_FILE"
    fi
else
    echo "This was a dry run. To actually merge, run:"
    echo "  $0 --backup"
fi

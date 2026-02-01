#!/bin/bash

# Script to export database and optionally convert URLs before export
# Usage: ./scripts/export-database-with-urls.sh [output-file] [local-url] [production-url]
# Example: ./scripts/export-database-with-urls.sh database-export.sql localhost:8080 terryshaguy.org

OUTPUT_FILE="${1:-database-export.sql}"
LOCAL_URL="${2:-localhost:8080}"
PRODUCTION_URL="${3:-terryshaguy.org}"

CONTAINER_NAME="tsm-theme-db"
DB_NAME="wordpress"
DB_USER="wordpress"
DB_PASSWORD="wordpress"

echo "Exporting database..."
echo ""

# Check if user wants to convert URLs before export
if [ -n "$2" ] && [ -n "$3" ]; then
    echo "⚠️  Converting URLs from '$LOCAL_URL' to '$PRODUCTION_URL' before export..."
    echo ""
    read -p "Continue? (y/N): " -n 1 -r
    echo ""
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        echo "Aborted."
        exit 1
    fi
    
    # First, convert URLs in the database
    SQL_SCRIPT=$(cat <<EOF
UPDATE terrysha_options SET option_value = REPLACE(option_value, '$LOCAL_URL', '$PRODUCTION_URL') WHERE option_name IN ('siteurl', 'home');
UPDATE terrysha_posts SET post_content = REPLACE(post_content, '$LOCAL_URL', '$PRODUCTION_URL');
UPDATE terrysha_posts SET guid = REPLACE(guid, '$LOCAL_URL', '$PRODUCTION_URL');
UPDATE terrysha_postmeta SET meta_value = REPLACE(meta_value, '$LOCAL_URL', '$PRODUCTION_URL');
UPDATE terrysha_comments SET comment_content = REPLACE(comment_content, '$LOCAL_URL', '$PRODUCTION_URL');
UPDATE terrysha_comments SET comment_author_url = REPLACE(comment_author_url, '$LOCAL_URL', '$PRODUCTION_URL');
EOF
)
    
    echo "Updating URLs in database..."
    echo "$SQL_SCRIPT" | docker exec -i "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" > /dev/null 2>&1
    
    if [ $? -ne 0 ]; then
        echo "✗ Error updating URLs. Exporting database with original URLs..."
    else
        echo "✓ URLs updated successfully!"
    fi
fi

# Export the database
echo ""
echo "Exporting database to $OUTPUT_FILE..."
echo "This may take a few minutes..."

if docker exec "$CONTAINER_NAME" mysqldump -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" > "$OUTPUT_FILE" 2>&1; then
    echo ""
    echo "✓ Database exported successfully!"
    echo ""
    echo "Output file: $OUTPUT_FILE"
    echo "File size: $(du -h "$OUTPUT_FILE" | cut -f1)"
    echo ""
    
    if [ -n "$2" ] && [ -n "$3" ]; then
        echo "⚠️  Note: URLs have been converted to production URLs."
        echo "   To revert back to local URLs, use:"
        echo "   ./scripts/update-wordpress-urls-to-dev.sh $PRODUCTION_URL $LOCAL_URL"
    else
        echo "To convert URLs in the exported file, you can:"
        echo "  1. Import the database"
        echo "  2. Use: ./scripts/update-wordpress-urls-to-dev.sh <production-url> <dev-url>"
    fi
else
    echo ""
    echo "✗ Export failed! Check the error above."
    exit 1
fi
